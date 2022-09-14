# monorepo-plugin
A plugin to facilitate a PHP monorepo approach, auto-requiring local repositories for you

## Usage

Using it in the root project

`composer require cnastasi/monorepo-plugin`

Using it in a submodule

`composer require cnastasi/monorepo-plugin --dev`

Inside the `composer.json` add this:
```
"extra": {
    "monorepo_path": "relative/path/where/the/module/are/located"
  }
```