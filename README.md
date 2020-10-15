# Lumen/Spotify API para buscar álbumes en spotify dado un nombre de artista.

## Official Documentation

1- Bajarse el proyecto y correr composer install.

2- Configurar las variables de entorno en el archivo env:

 SPOTIFY_CLIENT_ID=7cb348b021c54c92a26b89b596245410
 SPOTIFY_CLIENT_SECRET=c412bff361f141a191ec3c4475fa96c4

3- Levantar la app en un servidor web.

4- Invocar al endpoint.

<code>GET https://spotify_api.test/api/v1/albums?q=Bunbury
 
Bunbury es un ejemplo de artista, podemos cambiarlo por algun otro.

La respuesta que obtendremos es similar a esta:

```json
[
    {
        "name": "Hijos del pueblo",
        "released": "2015-04-14",
        "tracks": 10,
        "cover": [
            {
                "height": 640,
                "url": "https://i.scdn.co/image/ab67616d0000b273459bf80640ddf220ae68fe8c",
                "width": 640
            },
            {
                "height": 300,
                "url": "https://i.scdn.co/image/ab67616d00001e02459bf80640ddf220ae68fe8c",
                "width": 300
            },
            {
                "height": 64,
                "url": "https://i.scdn.co/image/ab67616d00004851459bf80640ddf220ae68fe8c",
                "width": 64
            }
        ]
    }
]
```

Nota: La busqueda esta limitada a 20 resultados.



