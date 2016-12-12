# PHP static analyzer

**WORK IN PROGRESS**

## Why?

PHP static analysis.

This analyzer is different because it isn't meant to find bugs in your code. It is intended as a framework for building your analyzer.

To summarize it in a few sentences:

> The goal is to create an open-source equivalent of PhpStorm's code analyzer. That framework could be used to write very advanced code analyzers, to query a codebase for specific searches (e.g. find all code that calls deprecated classes/methods), to bring PhpStorm's power into GitHub pull requests or even to improve other IDEs.

How it works:

1. **model a codebase using an [Abstract Syntax Tree](https://en.wikipedia.org/wiki/Abstract_syntax_tree) (aka *AST*)**

    The [PHP-AST](https://github.com/nikic/php-ast) extension is used as a base to parse the PHP code (requires PHP 7).
    
    However this project does not reuse nodes from PHP-AST: it implements new nodes. The reasons are:

    - our nodes are strictly typed, i.e. they are all different PHP classes which allows to write custom logic/rules for each node
    - our nodes are higher level: they are not meant to represent 1:1 the code, but rather provide a simpler (and more semantic) representation of the code (in short, it's simpler)
    - our nodes are extensible: they are meant to contain more data than just "the code", e.g. they can contain information contained in docblocks (e.g. the computed return type of a method, store if a method or a class is "deprecated", etc.)
        
2. **apply "visitors" on the AST**

    Visitors traverse the tree to enrich it with more data and logic. They can, for example, resolve fully qualified name of classes or functions based on the namespace of the file. They can also detect errors in the code. Or again they can try to guess the types of all variables and methods (type inference).
    
    Some default "visitors" are (will be) implemented to cover most use cases. However this package is intended as a framework: you can write custom visitors to enrich even more the information on a codebase (e.g. to add support for framework specific stuff like Laravel facades, Doctrine's entity manager, etc.).

3. **Serialize the AST**

    The AST (enriched with more data by visitors, or not) can be entirely serialized to JSON.
    
    That allows to incrementally parse a large codebase and re-parse only files that have changed.
    
    That also allows 3rd party tools to read the AST, e.g. to improve the autocompletion of an editor/IDE, or to build a code browser in HTML/Javascript that understands the code (e.g. `Ctrl+Click` in a browser).

4. **Build "real" applications on top of the AST**

    This project is meant as a base to write more useful applications for end users, as such it's just a library. If you are interested, open an issue.

### Is it fast?

No, for now the goal is to have something working. Then it will be made as fast as possible, just don't expect that to be a priority at first.

## Installation

```
composer require mnapoli/php-static-analyzer
```

## TODO

The amount of work is huge. If you want to help, hopefully this todo will help you find something to do.

- [ ] Find a cooler name

### 1. Model the PHP AST using our own classes

PHP-AST node types to support (list taken from [here](https://github.com/nikic/php-ast#ast-node-kinds)). Our nodes are in `src/Node/`.

- [ ] AST_ARRAY_ELEM
- [x] AST_ASSIGN
- [ ] AST_ASSIGN_OP
- [ ] AST_ASSIGN_REF
- [x] AST_BINARY_OP
- [ ] AST_BREAK
- [ ] AST_CALL
- [ ] AST_CAST
- [ ] AST_CATCH
- [x] AST_CLASS
- [ ] AST_CLASS_CONST
- [ ] AST_CLONE
- [ ] AST_CLOSURE
- [ ] AST_CLOSURE_VAR
- [ ] AST_COALESCE (will be removed in v>=40)
- [ ] AST_CONDITIONAL
- [x] AST_CONST
- [ ] AST_CONST_ELEM
- [ ] AST_CONTINUE
- [ ] AST_DECLARE
- [ ] AST_DIM
- [ ] AST_DO_WHILE
- [x] AST_ECHO
- [ ] AST_EMPTY
- [ ] AST_EXIT
- [ ] AST_FOR
- [ ] AST_FOREACH
- [ ] AST_FUNC_DECL
- [ ] AST_GLOBAL
- [ ] AST_GOTO
- [ ] AST_GROUP_USE
- [ ] AST_HALT_COMPILER
- [ ] AST_IF_ELEM
- [ ] AST_INCLUDE_OR_EVAL
- [ ] AST_INSTANCEOF
- [ ] AST_ISSET
- [ ] AST_LABEL
- [ ] AST_MAGIC_CONST      
- [x] AST_METHOD
- [x] AST_METHOD_CALL
- [ ] AST_METHOD_REFERENCE
- [x] AST_NAME
- [x] AST_NAMESPACE
- [x] AST_NEW
- [ ] AST_NULLABLE_TYPE
- [x] AST_PARAM
- [ ] AST_POST_DEC
- [ ] AST_POST_INC
- [ ] AST_PRE_DEC
- [ ] AST_PRE_INC
- [x] AST_PRINT
- [ ] AST_PROP
- [ ] AST_PROP_ELEM
- [ ] AST_REF
- [x] AST_RETURN
- [ ] AST_SHELL_EXEC
- [ ] AST_STATIC
- [ ] AST_STATIC_CALL
- [ ] AST_STATIC_PROP
- [ ] AST_SWITCH
- [ ] AST_SWITCH_CASE
- [x] AST_THROW
- [ ] AST_TRAIT_ALIAS
- [ ] AST_TRAIT_PRECEDENCE
- [ ] AST_TRY
- [ ] AST_TYPE             
- [ ] AST_UNARY_OP
- [ ] AST_UNPACK
- [ ] AST_UNSET
- [ ] AST_USE_ELEM
- [ ] AST_USE_TRAIT
- [x] AST_VAR
- [ ] AST_WHILE
- [ ] AST_YIELD
- [ ] AST_YIELD_FROM
- [ ] ZEND_AST_ARG_LIST
- [ ] ZEND_AST_ARRAY
- [ ] ZEND_AST_CATCH_LIST
- [ ] ZEND_AST_CLASS_CONST_DECL
- [ ] ZEND_AST_CLOSURE_USES
- [ ] ZEND_AST_CONST_DECL
- [ ] ZEND_AST_ENCAPS_LIST
- [ ] ZEND_AST_EXPR_LIST
- [ ] ZEND_AST_IF
- [ ] ZEND_AST_LIST
- [ ] ZEND_AST_NAME_LIST
- [x] ZEND_AST_PARAM_LIST
- [ ] ZEND_AST_PROP_DECL
- [x] ZEND_AST_STMT_LIST
- [ ] ZEND_AST_SWITCH_LIST
- [ ] ZEND_AST_TRAIT_ADAPTATIONS
- [ ] ZEND_AST_USE

- [ ] The project should also contain an AST of PHP's built-in functions and classes. It could be pre-computed and stored serialized to avoir recomputing it every time.

### 2. Visitors to implement

- [x] Resolve fully qualified names:`FqnVisitor`
    - [x] classes
    - [ ] functions
    - [ ] constants
- [x] Detect deprecated code:`DeprecationVisitor`
    - [x] classes
    - [ ] class methods
    - [ ] class properties
    - [ ] class constant
    - [ ] functions
    - [ ] constants
- [ ] Infer types:
    - [x] from primitive values (e.g. `1`, `'abc'`, ...)
    - [ ] from return types of function and methods in PHP >= 7.1
    - [ ] from return types of function and methods in phpdoc
    - [ ] from `return` statements of functions and methods
    - [ ] from operations (additions, concatenations, boolean operations, ...)
    - [ ] from variable assignments
    - [ ] from property assignments
    - [ ] from method calls
    - [ ] from property's phpdoc
    - [ ] from parameter's type-hint
    - [ ] from parameter's phpdoc type-hint
    - [ ] from type casting
    - [ ] from `catch (... $e)` type-hinting
    - [ ] from the `new` operator
    - [ ] from the `yield` operator
    - ...
- [ ] Detect common issues (the list below is to be expanded, I have only added a few examples):
    - [ ] method call on a non-object
    - [ ] call of an unknown method
    - [ ] call of an unknown function
    - ...

## License

This project is released under the MIT license.
