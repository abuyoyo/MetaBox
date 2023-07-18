# Changelog
WPHelper\MetaBox

## 0.8

### Fixed

- Fix `Metabox::render()` callback arguments. Callback is passed `$data_object` and `$box`.

## 0.7

### Fixed

- Validate `is_callable(render_cb)` before `call_user_func` call.

## 0.6

### Added

- Accept callable `render_cb` as well as readable `render_tpl` as render template.

## 0.5

### Added

- Basic Metabox API.
