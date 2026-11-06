@extends('layouts.base')

@section('title', 'Política de Privacidad | INFEST Chile')

@section('meta_description', 'Conoce cómo INFEST Chile protege tu privacidad y utiliza los permisos de cámara y almacenamiento en su aplicación móvil.')

@section('content')

<section class="max-w-4xl mx-auto px-6 py-16" style="margin:2%;">

    <header class="mb-12">
        <span class="text-sm font-semibold uppercase tracking-widest">
            INFEST Chile
        </span>

        <h1 class="text-4xl font-bold mt-3 mb-4">
            Política de Privacidad
        </h1>

        <p class="text-sm opacity-70">
            Última actualización: 6 de noviembre de 2026
        </p>
    </header>

    <div class="space-y-10 leading-relaxed">

        <section>
            <h2 class="text-xl font-bold mb-3">1. Introducción</h2>
            <p>
                INFEST Chile (en adelante, "INFEST") se compromete
                a respetar y proteger la privacidad de los usuarios
                de su aplicación móvil para Android.
            </p>
            <p class="mt-3">
                Esta Política de Privacidad explica qué información
                se utiliza, cómo se procesan los datos y qué permisos
                requiere la aplicación para proporcionar sus funcionalidades.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-3">2. Información recopilada</h2>
            <p>
                La aplicación INFEST Chile no requiere la creación
                de cuentas ni el registro de usuarios.
            </p>
            <p class="mt-3">
                No utilizamos herramientas de analítica, publicidad
                personalizada ni sistemas de seguimiento del
                comportamiento dentro de la aplicación.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-3">
                3. Uso de la cámara y lectura de códigos QR
            </h2>
            <p>
                La aplicación puede solicitar acceso a la cámara
                del dispositivo exclusivamente para permitir el
                escaneo y lectura de códigos QR.
            </p>
            <p class="mt-3">
                El contenido de los códigos QR se procesa directamente
                dentro de la aplicación para identificar la información
                asociada y ejecutar las funcionalidades correspondientes.
            </p>
            <p class="mt-3">
                La cámara no se utiliza para grabar videos, tomar
                fotografías personales ni realizar reconocimiento facial.
            </p>
            <p class="mt-3">
                Las imágenes capturadas durante el proceso de escaneo
                se utilizan únicamente para identificar el código QR
                y no se almacenan ni transmiten a servidores de INFEST Chile.
            </p>
            <p class="mt-3">
                El usuario puede administrar o revocar el permiso
                de cámara desde la configuración de Android.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-3">
                4. Descarga y almacenamiento de información
            </h2>
            <p>
                La aplicación permite descargar y guardar imágenes
                y contenido de texto directamente en el dispositivo
                del usuario.
            </p>
            <p class="mt-3">
                Esta funcionalidad tiene como finalidad facilitar
                el acceso y consulta de información relacionada
                con INFEST Chile.
            </p>
            <p class="mt-3">
                Los archivos descargados se almacenan localmente
                en el dispositivo y pueden permanecer disponibles
                después de cerrar la aplicación.
            </p>
            <p class="mt-3">
                El usuario puede administrar o eliminar los archivos
                descargados desde las opciones disponibles en su dispositivo.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-3">
                5. Compartición de información con terceros
            </h2>
            <p>
                INFEST Chile no vende, alquila ni comercializa
                información personal de los usuarios.
            </p>
            <p class="mt-3">
                El contenido obtenido mediante el escaneo de códigos QR
                se procesa dentro de la aplicación según la funcionalidad
                correspondiente.
            </p>
            <p class="mt-3">
                Las imágenes y textos descargados pueden provenir
                de servicios o fuentes externas. La consulta o descarga
                puede implicar comunicaciones técnicas con los servidores
                que proporcionan dichos contenidos.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-3">
                6. Seguridad de la información
            </h2>
            <p>
                Adoptamos medidas razonables para proteger el funcionamiento
                de la aplicación y la información utilizada por sus funcionalidades.
            </p>
            <p class="mt-3">
                La información descargada y almacenada localmente permanece
                bajo el control del usuario y está sujeta a las medidas
                de seguridad del dispositivo.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-3">
                7. Privacidad de menores de edad
            </h2>
            <p>
                La aplicación no está diseñada para recopilar
                intencionalmente información personal de menores de edad.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-3">
                8. Derechos de los usuarios
            </h2>
            <p>
                Los usuarios pueden dejar de utilizar la aplicación
                en cualquier momento y revocar los permisos concedidos
                desde la configuración de su dispositivo Android.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-3">
                9. Modificaciones de esta política
            </h2>
            <p>
                INFEST Chile podrá actualizar esta Política de Privacidad
                cuando existan cambios en las funcionalidades de la
                aplicación o en los requisitos legales aplicables.
            </p>
                Las modificaciones serán publicadas en esta misma
                dirección web, indicando la fecha de actualización.
        </section>

        <section>
            <h2 class="text-xl font-bold mb-3">10. Contacto</h2>
            <p>
                Si tienes consultas relacionadas con esta Política
                de Privacidad, puedes comunicarte con nosotros:
            </p>

            <ul class="mt-3 space-y-2">
                <li><strong>Organización:</strong> INFEST Chile</li>
                <li>
                    <strong>Sitio web:</strong>
                    <a href="https://infest.cl"
                       class="underline">
                        infest.cl
                    </a>
                </li>
                <li>
                    <strong>Correo:</strong>
                    <a href="mailto:informaciones@infest.cl"
                       class="underline">
                        informaciones@infest.cl
                    </a>
                </li>
            </ul>
        </section>

    </div>

</section>

@endsection