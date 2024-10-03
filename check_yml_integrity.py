"""
This script checks the ingegrity of the structure and (partially) the data of the eventy.yml YAML file.

@author: Jules Kreuer
@contact: contact@juleskreuer.eu
"""

from datetime import datetime
from schema import Schema, And, Use, Optional, SchemaError
import yaml

FILE_NAME = "events.yml"


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
            Optional("course_required"): bool
        },
        "icon": And(str, is_icon),
        Optional("metas"): [str],
    }
)


class UniqueKeyLoader(yaml.SafeLoader):
    """
    Load the YAML file, raises an error if a douplicate key is found.
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

exit(num_errors)
