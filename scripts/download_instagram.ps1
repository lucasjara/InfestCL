
$ErrorActionPreference = "Continue"

# Ejecutar desde la raíz del proyecto Laravel
$ProjectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $ProjectRoot

$Python = "py"

# Perfiles y carpetas de destino
$Profiles = @(
    @{
        Username = "infestchile"
        Directory = "public/images/INFEST"
    },
    @{
        Username = "infest_nightparty"
        Directory = "public/images/INFEST_Night_Party"
    }
)

# Verificar que Instaloader esté instalado
& $Python -m instaloader --version

if ($LASTEXITCODE -ne 0) {
    Write-Host "Instalando Instaloader..." -ForegroundColor Yellow
    & $Python -m pip install instaloader

    if ($LASTEXITCODE -ne 0) {
        throw "No se pudo instalar Instaloader."
    }
}

foreach ($Profile in $Profiles) {
    $Destination = $Profile.Directory
    $Username = $Profile.Username

    New-Item -ItemType Directory -Force -Path $Destination |
        Out-Null

    Write-Host "`nDescargando @$Username..." -ForegroundColor Cyan
    Write-Host "Destino: $Destination"

    & $Python -m instaloader `
        "--dirname-pattern=$Destination" `
        --no-videos `
        --no-captions `
        --no-metadata-json `
        $Username

    if ($LASTEXITCODE -eq 0) {
        Write-Host "Descarga finalizada: @$Username" `
            -ForegroundColor Green
    }
    else {
        Write-Host "La descarga de @$Username no se completó correctamente." `
            -ForegroundColor Yellow
    }
}

Write-Host "`nProceso finalizado." -ForegroundColor Cyan
