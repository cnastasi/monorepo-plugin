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
    "monorepo_paths": [
        "relative/path/where/the/modules/are/located",
        "another/relative/path/where/the/modules/are/located",
    ]
  }
```
or, for retrocompatibility, this:
```
"extra": {
    "monorepo_path": "relative/path/where/the/modules/are/located"
  }
```

Both ways are possible, but it is not recommended 

## Explanation
When you work with a mono repo in php, and you code is split in many packages required through composer, 
more packages you've got, more complexity you get.  

In fact, for every package you have to explicitly add a path repository, both for direct and undirect dependencies.

In order to help, this plugin will add for you all the dependencies path repositories, scanning al the directories inside a specific folder.

By convention, the folder structure expected is the following one 

```
 + root
 |
 +- module1
 |  +- composer.json
 |  +- ...
 +- module2
    +- composer.json
    +- ...
```
 

    NOTE: This is still a beta, use it at your own risk