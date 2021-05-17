## About
This document includes:
- the main POC requirements from Atalian
- data models and their relationships
- system features
- hardware information and clarification.

*_PS_: development of "display location history" feature is pushed back as it is not in the POC requirements of Atalian.*

*updated by: Grace, 10 Aug 2020*

## Requirements
- clock-in/clock-out
- display total time staying in the office (exclude lunch time)
- company information (display)
- scheduling
- different roles & permissions

#### Assumption
- Every user has a tag

## Models
- Company
- User
- Project
- Group
- Schedule
- Building
- Tag (Beacon)
- Reader

#### Extra
* Roles & Permissions (Spatie)
* Tag data (data from hardware)


#### Relationship
- company has users
- company has projects

- project has groups
- project has buildings

- group has users
- group has schedules

- user has roles
- role has permissions

- user has tags (not including admin) (many to many)
- user has attendances
- attendance needs user, schedule, tag, tag data, reader

- user has location histories (later)
- location history needs user, tag, tag data, reader (later)

- schedule has buildings
- building has readers

## Features
- Dashboard (tied to permissions)
- User management (CRUD) -G
- Roles and permissions (CRUD) -G
- Groups management (CRUD)
- Company managament (CRUD)
- Project management (CRUD)
- Building managemnet (CRUD)
- Schedule managemnet (CRUD)
- Tag (CRUD)
- Reader (CRUD)
- Location management (R)
- Attendance management (R)

- User
    * can be assigned to existing group
    * can add existing tag
    * import/export csv

- Group
    * can add existing schedule
    * can add existing users
    * import/export csv

- Schedule
    * can can be assigned to exisitng group
    * can add existing building
    * import/export csv

- Project 
    * can add exisitng group
    * can add existing building
    * import/export csv

- Building
    * can be assigned to project
    * can add exisiting readers
    * import/export csv

- Tag
    * can be assigned to user
    * import/export csv

- Reader
    * can be assigned to building
    * import/export csv

- Location History (later)
    * display location from user

- Attendance
    * display clockin/clockout time
    * display total hours stayed in the office

## Hardware
#### Reader (Gateway: Reader+4G Sim Card)
- Send tag(beacon) data to cloud in json format

#### JSON (Beacon)
- mac address (fixed, unique)
- UDID (can be modified)
- major
- minor
- power at 1 meter
- RSSI (in terms of radius)

#### Active RFID (not now)
- datime
- tag id
- ip address
- rssi: -88.0 (near:0, stronger, further: -100)
- trigger