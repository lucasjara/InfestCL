
import json
import re
import time
from pathlib import Path
from datetime import datetime, timezone

import instaloader
from instaloader import Profile

ROOT = Path(__file__).resolve().parent.parent
OUTPUT_DIR = ROOT / "docs" / "instagram-data"

# 0 = intentar recorrer todas las publicaciones accesibles.
# Puedes cambiarlo a 50 para probar primero.
MAX_POSTS = 0

PROFILES = {
    "INFEST": "infestchile",
    "INFEST_Night_Party": "infest_nightparty",
}


def extract_metadata(post):
    caption = post.caption or ""

    return {
        "shortcode": post.shortcode,
        "url": f"https://www.instagram.com/p/{post.shortcode}/",
        "username": post.owner_username,
        "date_utc": post.date_utc.isoformat(),
        "caption": caption,
        "hashtags": sorted(set(post.caption_hashtags)),
        "mentions": sorted(set(post.caption_mentions)),
        "likes": post.likes,
        "comments": post.comments,
        "is_video": post.is_video,
        "media_type": post.typename,
        "media_count": post.mediacount,
        "video_duration_seconds": (
            post.video_duration
            if post.is_video
            else None
        ),
        "location": (
            post.location.name
            if post.location
            else None
        ),
    }


def main():
    OUTPUT_DIR.mkdir(parents=True, exist_ok=True)

    loader = instaloader.Instaloader(
        download_pictures=False,
        download_videos=False,
        download_video_thumbnails=False,
        download_geotags=False,
        download_comments=False,
        save_metadata=False,
        compress_json=False,
    )

    for brand, username in PROFILES.items():
        output_file = OUTPUT_DIR / f"{username}.json"
        posts_data = []

        print(f"\nAnalizando @{username}...")

        try:
            profile = Profile.from_username(
                loader.context, username
            )

            for index, post in enumerate(profile.get_posts(), start=1):
                if MAX_POSTS and index > MAX_POSTS:
                    break

                try:
                    posts_data.append(extract_metadata(post))

                    # Guardar avances por si la consulta se interrumpe.
                    output_file.write_text(
                        json.dumps(
                            {
                                "brand": brand,
                                "username": username,
                                "extracted_at": datetime.now(
                                    timezone.utc
                                ).isoformat(),
                                "posts_count": len(posts_data),
                                "posts": posts_data,
                            },
                            ensure_ascii=False,
                            indent=2,
                        ),
                        encoding="utf-8",
                    )

                    print(
                        f"[{index}] {post.date_utc.date()} "
                        f"| {post.shortcode}"
                    )

                except Exception as error:
                    print(
                        f"No se pudo leer una publicación: {error}"
                    )

            print(
                f"Finalizado @{username}: "
                f"{len(posts_data)} publicaciones procesadas."
            )
            print(f"Archivo: {output_file}")

        except Exception as error:
            print(f"Error con @{username}: {error}")
            print(
                "Instagram puede exigir autenticación o limitar "
                "temporalmente las consultas."
            )

        # Pausa entre perfiles para reducir consultas consecutivas.
        time.sleep(3)


if __name__ == "__main__":
    main()