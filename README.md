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

## XML
XML Configuration example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<esRadApp>
    <events>
        <when description="Thing is sent" eventName="ThingSent">
            <sideEffects>
                <create entity="Thing">
                    <propertyMappings>
                        <propertyMap eventProperty="createEventProp1" entityProperty="createEntityProp1"/>
                        <propertyMap eventProperty="createEventProp2"
                                     eventPropertyType="int"
                                     entityProperty="createEntityProp2"
                                     entityPropertyType="int"
                        />
                    </propertyMappings>
                </create>
            </sideEffects>
        </when>
        <when description="Thing is updated" eventName="ThingUpdated">
            <sideEffects>
                <update entity="Thing">
                    <propertyMappings>
                        <propertyMap eventProperty="updateEventProp1"
                                     eventPropertyType="string"
                                     entityProperty="updateEntityProp1"
                                     entityPropertyType="string"/>
                    </propertyMappings>
                </update>
            </sideEffects>
        </when>
        <when description="Thing is deleted" eventName="ThingDeleted">
            <sideEffects>
                <delete entity="Thing">
                    <propertyMappings>
                        <propertyMap eventProperty="deleteEventProp1" entityProperty="deleteEntityProp1"/>
                    </propertyMappings>
                </delete>
            </sideEffects>
        </when>
    </events>
</esRadApp>
```

### Code Generated

TODO