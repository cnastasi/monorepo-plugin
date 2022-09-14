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

## Explanation
When you work with a mono repo in php, and you code is split in many packages required through composer, 
more packages you've got, more complexity you get.  

In fact, for every package you have to explicitly add a path repository, both for direct and undirect dependencies.

In order to help, this plugin will add for you all the dependencies path repositories, scanning al the directories inside a specific folder.

    NOTE: This is still a beta, use it at your own risk