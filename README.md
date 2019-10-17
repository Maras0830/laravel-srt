# Laravel SRT

## SRT = Service + Repository + Transformer
Generator tool

---

## Installation

```shell
$ composer require maras0830/laravel-srt
```

## USAGE

### Make Service
```
$ php artisan make:service UserService
```

### Make Repository
```
$ php artisan make:repository UserRepository
```

### Make Transformer
```
$ php artisan make:transformer UserTransformer
```

### Make Service+Repository+Transformer
```
$ php artisan make:srt User
```

### Eager query check & Strict Mode

in your CustomerTransformer file
```
    public function __construct()
    {
    	// set your eager query required relations.
        $this->setRequiredRelations(['notificationable']);

        // false: laravel-srt log warning when you forgot eager query( ->with(['notificationable']) )
        // true: laravel-srt throw TransformerException when you forgot eager query( ->with(['notificationable']) )
        $this->setStrictMode(false);
    }
```

