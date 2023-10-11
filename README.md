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

## Complete Project

Cut and paste to create an new Symfony project with a dynamic, searchable datatable, without writing a single line of Javascript!  No webpack or build step either.

```bash
symfony new simple-datatables-demo --webapp && cd simple-datatables-demo
composer req symfony/asset-mapper
composer req symfony/stimulus-bundle:2.x-dev
composer req survos/simple-datatables-bundle
bin/console importmap:require bootstrap
bin/console make:controller AppController
sed -i "s|Route('/app'|Route('/'|" src/Controller/AppController.php

cat > templates/app/index.html.twig <<END
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
```



