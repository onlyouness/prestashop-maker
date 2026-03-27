{{route_name}}:
  path: {{route_path}}
  methods: [GET, POST]
  defaults:
    _controller: '{{controller_fqcn}}::index'
    _legacy_controller: {{legacy_controller}}
    _legacy_link: {{legacy_controller}}