[![Type Coverage](https://shepherd.dev/github/theshaunwalker/es-rad-app-generator/coverage.svg)](https://shepherd.dev/github/theshaunwalker/es-rad-app-generator)


Generates the code for an event driven and event sourced prototype backend, based on an XML configuration. 
Other XML providers can be implemented to support other methods of configuration.

Generates:
- Entities
- Entity Repositories (sans database code for now)
- Events
- Event Listeners

Future generation ideas:
- [ ] Database plugins to generate database code
- [ ] HTTP Api endpoints

# Example

See `demo` directory for example of native config XML and its generated code.

# Usage

This is still super early WIP so no polished distributable yet. But you can clone this repo and run `bin/esapp <app_dir>` to try it out yourself.
`app_dir` being a directory containing an `esradapp.xml` file