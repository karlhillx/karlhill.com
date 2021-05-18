# Full Stack Developer | Karl Hill

Foobar is a Python library for dealing with word pluralization.

## Installation

In the project root, change the working directory to laradock/.

    cd laradock/

Enter the Laradock folder and copy .env.example to .env.

    cp .env.example .env

Build the environment and run it using docker-compose.

    docker-compose up -d apache2

Enter the Workspace container, to execute commands like (Artisan, Composer, PHPUnit, Gulp, …)

    docker-compose exec workspace bash

Stop and remove containers, networks and volumes.

    docker-compose down -v

Remove all unused containers, networks, images (both dangling and unreferenced), and optionally, volumes.

    docker system prune --volumes

## Usage

```python
import foobar

foobar.pluralize('word') # returns 'words'
foobar.pluralize('goose') # returns 'geese'
foobar.singularize('phenomena') # returns 'phenomenon'
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
