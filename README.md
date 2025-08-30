# 🍎🥕 Fruits and Vegetables

## 🎯 Goal
We want to build a service which will take a `request.json` and:
* Process the file and create two separate collections for `Fruits` and `Vegetables`
* Each collection has methods like `add()`, `remove()`, `list()`;
* Units have to be stored as grams;
* Store the collections in a storage engine of your choice. (e.g. Database, In-memory)
* Provide an API endpoint to query the collections. As a bonus, this endpoint can accept filters to be applied to the returning collection.
* Provide another API endpoint to add new items to the collections (i.e., your storage engine).
* As a bonus you might:
  * consider giving an option to decide which units are returned (kilograms/grams);
  * how to implement `search()` method collections;
  * use latest version of Symfony's to embed your logic 

### ✔️ How can I check if my code is working?
You have two ways of moving on:
* You call the Service from PHPUnit test like it's done in dummy test (just run `bin/phpunit` from the console)

or

* You create a Controller which will be calling the service with a json payload

## 💡 Hints before you start working on it
* Keep KISS, DRY, YAGNI, SOLID principles in mind
* We value a clean domain model, without unnecessary code duplication or complexity
* Think about how you will handle input validation
* Follow generally-accepted good practices, such as no logic in controllers, information hiding (see the first hint).
* Timebox your work - we expect that you would spend between 3 and 4 hours.
* Your code should be tested
* We don't care how you handle data persistence, no bonus points for having a complex method

## When you are finished
* Please upload your code to a public git repository (i.e. GitHub, Gitlab)

## 🐳 Docker image
Optional. Just here if you want to run it isolated.

### 📥 Pulling image
```bash
docker pull tturkowski/fruits-and-vegetables
```

### 🧱 Building image
```bash
docker build -t tturkowski/fruits-and-vegetables -f docker/Dockerfile .
```

### 🏃‍♂️ Running container
```bash
docker run -it -w/app -v$(pwd):/app tturkowski/fruits-and-vegetables sh 
```

### 🛂 Running tests
```bash
docker run -it -w/app -v$(pwd):/app tturkowski/fruits-and-vegetables bin/phpunit
```

### ⌨️ Run development server
```bash
docker run -it -w/app -v$(pwd):/app -p8080:8080 tturkowski/fruits-and-vegetables php -S 0.0.0.0:8080 -t /app/public
# Open http://127.0.0.1:8080 in your browser
```

## Additional Documentations

### Development Commands

The most important commands are available as Make targets:

```bash
# Open a container shell
make connect

# Run PHPUnit tests
make tests

# Start the development server (runs in background on port 8080)
make server_up

# View development server logs (follow mode)
make server_logs

# Stop and remove the development server container
make server_down

# composer install && update on the container
make composer

# composer dump-autoload on the container
make dumpautoload
```

### Time Summary

* Initial triage (5 min): Skimmed the challenge and clarified scope.
* Repository review (10 min): Read composer.json, sample request.json, and documentation to understand domain and expected I/O.
* Environment setup (20 min): Reviewed an AI-proposed solution and other forks for context; brought Docker containers up locally.
* Build tooling (10 min): Created and validated a Makefile to streamline install, test, and run tasks.
* First test pass (15 min): Wrote an initial test for item generation to anchor the domain model.
* Coding & Quality of Life(75 min): Implemented Item and Weight classes. Added unit tests for Weight, expanded Makefile with Composer targets, ensured tests pass, updated documentation, and pushed the first commit.
* Collections (30 min): Implemented FruitsCollection with corresponding unit tests.
* Time is running out (60 min): Added an integration test that processes a request payload and reloads data from a serialized .dat file via the file store. Created minimal implementation with AI assistance

Total time spent: ~3h 45m

Not counting the final documentation. Within the left 15 Minutes i will not be able to finish all goals.

Next Steps:
- Create API Integrationtest, Controller and Routes, Postman/Swagger Dokumentation
- Fix linting, codestyle, missing tests

### How can you verify my code works?
Look into RequestJsonProcessorFileStoreTest. It's commented, It shows processing and generation of the Collections as well as loading from a store
