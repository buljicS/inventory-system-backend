# Environment variables

## Install dependencies
- Install `phpdotenv` via composer to your project
<tabs>
<tab title="Install phpdotenv">
    <code-block lang="bash">composer require vlucas/phpdotenv</code-block>
</tab>
</tabs>

## Create env file in your project
- In your project root create `.env`

<code-block lang="apache">
# .env file
JWT_SECRET='jwtsignature2024'
DB_DSN='mysql:host=myhost;dbname=database;charset=utf8' #mariadb connection string
</code-block>

- In the same manner create `.env.example` file

<code-block lang="apache">
# .env.example file 
JWT_SECRET='secret'
DB_DSN='mysql:host=example;dbname=example;charset=utf8' #mariadb connection string
</code-block>

### Note
<tip >
  .env.example serves as template for creating real .env file, since .env file itself will be ignored by version control system
</tip>