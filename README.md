1. Set Up Your Environment

```
composer install
```

2. Set up Api Keys to the .env file

```
GOOGLE_TRANSLATOR_API_KEY=your_google_translator_api_key
DEEPL_TRANSLATOR_API_KEY=your_deepl_translator_api_key
```

3. Create Input File. Create a file named `input.txt`, for example, in the root directory with some text lines to
   translate.

4. Run the Command:

```
php bin/console app:translate input.txt output.txt --translator=App\Service\Translator\GoogleTranslator --targetLang=ua
```

- `input.txt`: Path to the input file containing text lines to translate.
- `output.txt`: Path to the output file where the translated text will be saved.
- `--translator`: The fully qualified class name of the translator service to use (`GoogleTranslator`
  or `DeepLTranslator`).
- `--targetLang`: The target language code (e.g.,`ua` for Ukraine, `es` for Spanish, `fr` for French).
