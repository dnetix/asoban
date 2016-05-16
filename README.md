# Asobancaria Parser

Reads asobancaria plain text files and parse them into AsobanResults.

### Examples

Parsing Asobancaria 2001
```
$result = \Dnetix\Asoban\AsobanParser::load(FILE_PATH)->parse();
```

Parsing Asobancaria 1998
```
$result = \Dnetix\Asoban\AsobanParser::load(FILE_PATH, \Dnetix\Asoban\AsobanParser::F_1998)->parse();
```

At the moment parses Asobancaria 2001 and 1988 formats

### TODO

* Better handling of the batchs for the 2001 format