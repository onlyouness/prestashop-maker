{% extends '@PrestaShop/Admin/layout.html.twig' %}

{% block content %}
{{ form_start({{form_view_var}}) }}
<div class="card">
  <h3 class="card-header">
    <i class="material-icons">settings</i> {{ '{{form_title}}'|trans({}, 'Modules.{{module_pascal}}.Admin') }}
  </h3>
  <div class="card-body">
    <div class="form-wrapper">
      {{ form_widget({{form_view_var}}) }}
    </div>
  </div>
  <div class="card-footer">
    <div class="d-flex justify-content-end">
      <button class="btn btn-primary float-right" id="save-button">
        {{ 'Save'|trans({}, 'Admin.Actions') }}
      </button>
    </div>
  </div>
</div>
{{ form_end({{form_view_var}}) }}
{% endblock %}

{% block javascripts %}
{# {{ parent() }} #}
<script type="text/javascript">
  $(() => {
    window.prestashop.component.initComponents([
      'TranslatableInput', 'TinyMCEEditor'
    ])
  })
</script>
{% endblock %}