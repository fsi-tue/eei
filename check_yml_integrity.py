"""
This script checks the ingegrity of the structure and (partially) the data of the eventy.yml YAML file and the translations keys (de.json / en.json).

@author: Jules Kreuer
@contact: contact@juleskreuer.eu
"""

from datetime import datetime
from schema import Schema, And, Use, Optional, SchemaError
import yaml
import os
import json

FILE_NAME = "events.yml"
LANG_FILES = ["i18n/de.json", "i18n/en.json"]

UNIQUE_VALUES = {}
DATE_FORMAT = "%d.%m.%Y %H:%M"


def is_date(s: str) -> bool:
    """
    Must follow %d.%m.%Y %H:%M format
    Eg: 07.03.2024 01:02
    """
    try:
        datetime.strptime(s, DATE_FORMAT)
    except ValueError:
        print(f"The date {s} does not follow the format {DATE_FORMAT}")
        return False
    return True


def is_unique(key, data) -> bool:
    """
    Check if `data` for a given `key` is unique across all events.
    """
    if key not in UNIQUE_VALUES:
        UNIQUE_VALUES[key] = [data]
        return True

    if data not in UNIQUE_VALUES[key]:
        UNIQUE_VALUES[key].append(data)
        return True

    return False


def is_icon(s: str) -> bool:
    valid_icon_names = [
        "beer",
        "cap",
        "clock",
        "cocktail",
        "dice",
        "film",
        "food",
        "grill",
        "hiking",
        "home",
        "marker",
        "route",
        "signs",
        "snowflake",
    ]
    return s in valid_icon_names


# Schema of a single event
# To check the value for:
#    a string use: str
#    a non-empty string use: And(str, len),
#    a date use: And(str, is_date),
#    a unique field use: lambda s: is_unique("KEY NAME", s)
#    a list of str use: [str]
#
# To check for optional keys use: Optionale("KEY NAME"): VALUE
REQUIRED_SCHEMA = Schema(
    {
        Optional("name"): And(str, len),
        Optional("cancelled"): bool,
        Optional("text"): str,
        Optional("info"): str,
        "location": str,
        "max_participants": int,
        Optional("dinos"): bool,
        "event_date": {
            "start": And(str, is_date),
            Optional("end"): And(str, is_date),
            "onTime": bool,
        },
        "registration_date": {
            Optional("start"): And(str, is_date),
            "end": And(str, is_date),
        },
        "csv_path": And(str, len, lambda s: is_unique("csv_path", s)),
        Optional("form"): {
            Optional("breakfast"): bool,
            Optional("food"): bool,
            Optional("gender"): bool,
            Optional("course_required"): bool,
            Optional("drinks_alcohol"): bool,
        },
        "icon": And(str, is_icon),
        Optional("metas"): [str],
    }
)


class UniqueKeyLoader(yaml.SafeLoader):
    """
    Load the YAML file, raises an error if a duplicate key is found.
    Adapted from https://gist.github.com/pypt/94d747fe5180851196eb
    """

    def construct_mapping(self, node, deep=False):
        mapping = set()
        for key_node, value_node in node.value:
            if ":merge" in key_node.tag:
                continue
            key = self.construct_object(key_node, deep=deep)
            if key in mapping:
                raise ValueError(f"Duplicate {key!r} key found in YAML.")
            mapping.add(key)
        return super().construct_mapping(node, deep)


def check_language_files() -> int:
    """
    Check the integrity of the language JSON files.
    Ensures:
    - Files exist.
    - Files are valid JSON.
    - Keys in both files match.
    """
    errors = 0
    lang_data = {}

    # Check if files exist and are valid JSON
    for lang_file in LANG_FILES:
        if not os.path.exists(lang_file):
            print(f"Error: Language file {lang_file} does not exist.")
            errors += 1
            continue

        try:
            with open(lang_file, "r", encoding="utf-8") as f:
                lang_data[lang_file] = json.load(f)
        except json.JSONDecodeError as e:
            print(f"Error: Language file {lang_file} is not valid JSON. {e}")
            errors += 1

    # Compare keys between language files
    if len(lang_data) == len(LANG_FILES):
        keys = [set(data.keys()) for data in lang_data.values()]
        if keys[0] != keys[1]:
            print("Error: Keys in language files do not match.")
            print(f"Keys in {LANG_FILES[0]} but not in {LANG_FILES[1]}: {keys[0] - keys[1]}")
            print(f"Keys in {LANG_FILES[1]} but not in {LANG_FILES[0]}: {keys[1] - keys[0]}")
            errors += 1

    return errors


with open(FILE_NAME, "r") as f:
    yaml_data = yaml.load(f, Loader=UniqueKeyLoader)

max_key_len = max(len(k) for k in yaml_data["events"].keys()) + 1
num_errors = 0

for key, event in yaml_data["events"].items():
    try:
        REQUIRED_SCHEMA.validate(event)
        print(f" {key.ljust(max_key_len)} valid.")
    except SchemaError as e:
        num_errors += 1
        e = str(e).replace("\n", " ")
        print(f" {key.ljust(max_key_len)} Invalid schema. {e}")

# Add the language file check to the main script
num_errors += check_language_files()
exit(num_errors)
