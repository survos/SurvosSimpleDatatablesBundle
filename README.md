# Survos Simple Datatables Bundle

Integrate the Simple Datatables library from https://github.com/fiduswriter/simple-datatables/ as a stimulus component.


```bash
composer req survos/simple-datatables-bundle
```

## Add the stimulus controller

To change any html table into a datatable, simple add the stimulus controller to the tag

```twig
     <table class="table" {{ stimulus_controller('@survos/simple-datatables-bundle/table', {perPage: 5, sortable: true}) }}>
```

# BUG
```bash
symfony new bug  --webapp --version=next --php=8.2 && cd bug
composer config extra.symfony.allow-contrib true
composer req symfony/asset-mapper:^6.4
bin/console importmap:require twig
bin/console make:controller Bug -i
cat > templates/bug.html.twig <<END
{% extends 'base.html.twig' %}
{% block javascripts %}
    {{ parent() }}
    <script type="module">
        import Twig from 'twig';
{% verbatim %}
        var template = Twig.twig({
            data: 'Hello, {{ name }}.'
        });
        document.getElementById("#hello").innerHTML = template.render({name: 'Tac'});
{% endverbatim %}
    </script>
{% endblock %}
{% block body %}<div id="hello">hello</div>{% endblock %}

END
symfony server:start -d
symfony open:local --path=/bug
```
## Complete Project

Cut and paste to create an new Symfony project with a dynamic, searchable datatable, without writing a single line of Javascript!  No webpack or build step either.

```bash
symfony new simple-datatables-demo --webapp --version=next --php=8.2 && cd simple-datatables-demo
composer config minimum-stability dev
composer config extra.symfony.allow-contrib true
composer req symfony/asset-mapper:^6.4
composer req symfony/stimulus-bundle:2.x-dev

composer req survos/simple-datatables-bundle
bin/console make:controller Simple -i
cat > templates/simple.html.twig <<END
{% extends 'base.html.twig' %}

{% block body %}
     <table class="table" {{ stimulus_controller('@survos/simple-datatables-bundle/table', {perPage: 5, sortable: true}) }}>
        <thead>
        <tr>
            <th>abbr</th>
            <th>name</th>
            <th>number</th>
        </thead>
        <tbody>
        {% for j in 1..12 %}
            <tr>
                <td>{{ j |date('2023-' ~ j ~ '-01') |date('M') }}</td>
                <td>{{ j |date('2023-' ~ j ~ '-01') |date('F') }}</td>
                <td>{{ j }}</td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
{% endblock %}
END
symfony server:start -d
symfony open:local --path=/simple
```



## Problem in package.json

```json
  "symfony": {
    "controllers": {
      "table": {
        "main": "src/controllers/table_controller.js",
        "webpackMode": "eager",
        "fetch": "lazy",
        "enabled": true,
        "autoimport": {
          "@survos/simple-datatables/style.css": true <-- what is this supposed to be?
        }
      }
    },

```
