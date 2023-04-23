# Coopincuba API Restful
---

`coop-auth` es la api para acceder a todos los recursos y operaciones de la cooperativa.

##### ⚡Vea la documentación completa del servicio [aqui](https://github.com/alf46/coop-auth/issues/1).

[![My Skills](https://skillicons.dev/icons?i=linux,git,docker,php,mysql&perline=5)](https://skillicons.dev)

#### Get Source Code
```bash
git clone https://github.com/alf46/coop-auth.git

# install packages
composer install
```

#### Startup
```bash
# for linux
make dev

# for windows
docker compose up
```

#### User role
Existen dos roles de usuarios: `adm` y `socio`.
El rol `adm` puede acceder a todos los endpoint, mientras que el rol `socio` no puede acceder a los endpoint marcado com `adm`.

#### Login
- *POST /api/v1/auth*
- *Content-Type: application/json*

*Body:*
```json
{
    "username": "{username}",
    "password": "{password}"
}
```

*Response:*
```json
{
    "access_token": "{bearer token}"
}
```

#### User Info
- *GET /api/v1/me*

*Response:*
```json
{
    "username": "{username}",
    "email": "{email}",
    "role": "{role}"
}
```

#### Get User By Username
- *GET /api/v1/user/{id}*

*Response:*
```json
{
    "username": "{username}",
    "email": "{email}",
    "role": "{role}",
    "created_at": "{created_at}",
    "enabled": {enabled}
}
```

#### Create New User
- *POST /api/v1/user*

*Body*:
```json
{
    "username": "{username}",
    "password": "{password}",
    "email": "{email}",
    "role": "{role}"
}
```

*Response:*
```json
{
    "username": "{username}",
    "email": "{email}",
    "role": "{role}",
    "created_at": "{created_at}",
    "enabled": 1
}
```

#### Forgotten Password
- *POST /api/v1/auth/forgot*

*Body*:
```json
{
    "username": "{username}",
}
```

*Response:*
```json
{
    "email": "{email}",
}
```

Este endpoint envia un correo al email del usuario.
Posteriolmente, el usuario debe ingresar a su correo y pulsar el boton "RESTABLECER MI CONTRASEÑA".

#### Password Recovery
- *POST /api/v1/auth/recovery*

*Body*:
```json
{
    "code": "{code}",
    "password": "password"
}
```