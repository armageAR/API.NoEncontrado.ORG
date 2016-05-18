# API.NoEncontrado.ORG
API de NoEncontrado.org

La version actual de nuestra API esta basada en el codigo Jacwright API 

URL                           HTTP Method   Operation
/api/search                   GET           Obtiene los datos, de forma aleatoria, de una de las busquedas activas
/api/last                     GET           Obtiene los datos de todas las busquedas activas
/api/last/:n                  GET           Obtiene los datos de las ultimas n busquedas activas



URL de aceso Publico
http://api.noencontrado.org/v1/search/

Datos obtenidos
    "data": {
        "id": "0ed1f9a348c6765a279412f8d2d1cbf3",
        "nombre": "CAMILA",
        "apellido": "CINALL",
        "fecha_desaparicion": "2015-08-30",
        "fecha_foto": "",
        "edad_foto": 15,
        "fecha_nacimiento": "2000-02-02",
        "residencia": "San Miguel del Monte, Buenos Aires",
        "edad": 16
    }
}

Imagen
Para obtener la imagen de la busqueda se debe conformar de la siguiente forma
http://static.noencontrado.org/img/[ID].jpg
