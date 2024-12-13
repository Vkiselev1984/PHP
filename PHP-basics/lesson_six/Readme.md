# Database

Attention! Until the problem of copying the database to the local host is solved, and with each build it is necessary to go into the database container and create a table with the contents in it.

We already have configured environment, which includes containers with nginx and php-fpm. Of course, we will need separate container for PostgreSQL.

As you probably remember, container does not guarantee safety of information after stopping. But database must be reliable storage. Sometimes they say - persistent. This is storage that ensures preservation of data on long-term basis, even after restart or shutdown of system.

In other words, PostgreSQL must be able to save state of its data even when container is restarted. And this means that we will need volume in which files of database itself will be stored.

Also, when building our image for first time, you will need to specify name of database that our application will use, username and password.

Of course, they can be specified explicitly in docker-compose, but this is not correct and safe approach. This is sensitive data, which must be stored separately.

Fortunately, docker supports environment files that are not usually committed explicitly to repository. This is .env (dot-env) file approach.

Env file in context of Docker is configuration file that contains environment variables used in your Docker container. .env file is often used to centrally store sensitive data and configuration settings, such as passwords, API keys, and other sensitive data.

Format of .env file is simple: each line contains an environment variable in format "KEY=VALUE":

```
DB_HOST=localhost
DB_USER=myuser
DB_PASSWORD=mypassword
```

We add .env to .gitignore file, so that it is not committed to repository.

In [docker-compose](/docker-compose.yaml), these variables are called as follows:

```
database:
    image: postgres:9.6.1
    container_name: database
    environment:
      POSTGRES_DB: ${DB_NAME}
      POSTGRES_USER: ${DB_USER}
      POSTGRES_PASSWORD: ${DB_PASSWORD}
    ports:
      - "5432:5432"
    volumes:
        - ./db:/var/lib/postgres
    networks:
      - app-network

```

And next to docker-compose file, create .env file:

```
DB_HOST=database
DB_NAME=application1
DB_USER=root
DB_PASSWORD=root
DSN="pgsql:host=database;port=5432;dbname=application"
```

## Install PostgreSQL client

1. Update package list:

```
sudo apt update
```

2. Install PostgreSQL:

```
sudo apt install postgresql-client
```

3. To install full PostgreSQL server along with client, use:

```
sudo apt install postgresql
```

4. Verify installation:

After installation, check PostgreSQL client version by running:

```
psql --version
```

Specify in cli [Dockerfile](/cli/Dockerfile) need to install client:

```
RUN apt-get update && apt-get install -y \
    postgresql-client \
    && rm -rf /var/lib/apt/lists/*
```

## Layers

Let's break our application into layers. Let's create next folders:

- Application for [Application](/code/src/Application/Application.php) (update const APP_NAMESPACE) and [Render](/code/src/Application/Render.php) (update variable $viewFolder )
- Domain folders to store MVC model
- Infrastructure where we creat files to organize application configuration management when interacting with database [Config](/code/src/Infrastructure/Config.php) (loads DSN, user_name and password from config.ini file) and [Storage](/code/src/Infrastructure/Storage.php) (responsible for managing database connection using PDO).

Don't forget to update namespace, because location of files will now be different.

## Create new database

1. Login to PostgreSQL server as superuser:

```
sudo -u postgres psql
```

2. Create new database:

```
CREATE DATABASE application1;
```

## Connect to database

1. Log in to PostgreSQL server as superuser:

```
sudo -u postgres psql
```

2. Connect to database:

```
\c application1
```

## Create table

To create new table:

```
CREATE TABLE users (
    id_user SERIAL PRIMARY KEY,
    user_name VARCHAR(100),
    user_lastname VARCHAR(100),
    user_birthday_timestamp INT
);
```

### Insert data:

```
INSERT INTO users (user_name, user_lastname, user_birthday_timestamp)
VALUES ('Иван', 'Иванов', EXTRACT(EPOCH FROM TIMESTAMP '1990-01-01 00:00:00'));
```

### Update data:

Let's add method to update user by http request in class [User](/code/src/Domain/User.php):

```php
public function updateUser(array $userDataArray): void
    {
        unset($userDataArray['id_user']);

        $sql = "UPDATE users SET ";

        $counter = 0;
        foreach ($userDataArray as $key => $value) {
            $sql .= $key . " = :" . $key;

            if ($counter != count($userDataArray) - 1) {
                $sql .= ",";
            }

            $counter++;
        }

        $sql .= " WHERE id_user = :id_user";

        $userDataArray['id_user'] = $this->idUser;

        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute($userDataArray);
    }
```

Let's write method to handle an HTTP request in UserController class:

```php
public function actionUpdate(): string
    {
        if (!isset($_GET['id']) || !User::exists($_GET['id'])) {
            throw new \Exception("User does not exist");
        }

        $user = new User();
        $user->setUserId($_GET['id']);

        $arrayData = [];

        if (isset($_GET['name']) && !empty($_GET['name'])) {
            $arrayData['user_name'] = $_GET['name'];
        }

        if (isset($_GET['lastname']) && !empty($_GET['lastname'])) {
            $arrayData['user_lastname'] = $_GET['lastname'];
        }

        if (empty($arrayData)) {
            throw new \Exception("No data to update");
        }

        $user->updateUser($arrayData);

        $render = new Render();
        return $render->renderPage(
            'user-created.twig',
            [
                'title' => 'User updated',
                'message' => "User updated " . $user->getUserId()
            ]
        );
    }
```

1. Method first checks if user with specified id exists. If not, an exception is thrown.
   ![request](/img/update.png) is used to get DSN, user_name and password from config.ini file.
2. If user exists, new User object is created and its id is set. Then data for updating (first and last name) is collected from request parameters.
3. UpdateUser method is called to update user data in database.
4. After successful update, page is returned with message about successful update.
   ![update_result](/img/update_result.png)
   ![user_list](/img/user_list.png)

For request like http://mysite.local/user/update/?id=1&name=Ivan:

- id is user ID for which data needs to be changed
- name is name value to which user with specified id needs to be changed

### Delete data:

Let's write method for delete data by http request:

- check if data with an identifier exists
- delete data by given identifier

```php
public static function deleteFromStorage(int $user_id): void
    {
        $sqlCheck = "SELECT COUNT(*) FROM users WHERE id_user = :id_user";
        $handlerCheck = Application::$storage->get()->prepare($sqlCheck);
        $handlerCheck->execute(['id_user' => $user_id]);

        $exists = $handlerCheck->fetchColumn();

        if ($exists == 0) {
            throw new \Exception("User with ID {$user_id} not found.");
        }

        $sql = "DELETE FROM users WHERE id_user = :id_user";
        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute(['id_user' => $user_id]);
    }
```

Let's write method to handle an HTTP request in UserController class:

```php
public function actionDelete(): string
    {
        if (User::exists($_GET['id'])) {
            User::deleteFromStorage($_GET['id']);

            $render = new Render();

            return $render->renderPage(
                'user-removed.twig',
                []
            );
        } else {
            throw new \Exception("User does not exist");
        }
    }
```
