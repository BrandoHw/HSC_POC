## About
This document is the database schemas for Atalian POC project.

*_PS_: "tag_data_log" table is not confirmed .*

*updated by: Grace, 10 Aug 2020*

# companies

    id                  bigint          primary, unsigned
    name                string
    detail              string          nullable
    created_at          timestamp
    updated_at          timestamp

# company_has_projects

    project_id          bigint          foreign -> projects.id
    company_id          bigint          foreign -> companies.id

    primary(['project_id','company_id], 'company_has_projects_project_id_company_id_primary')

# company_has_users

    user_id             bigint          foreign -> users.id
    company_id          bigint          foreign -> companies.id

    primary(['user_id','company_id], 'company_has_users_user_id_company_id_primary')

# projects

    id                  bigint          primary, unsigned
    name                string
    detail              string          nullable
    start_date          date
    end_date            date
    created_at          timestamp
    updated_at          timestamp

# project_has_groups

    group_id            bigint          foreign -> groups.id
    project_id          bigint          foreign -> projects.id

    primary(['group_id','project_id], 'project_has_groups_group_id_project_id_primary')

# groups

    id                  bigint          primary, unsigned
    name                string
    detail              string          nullable
    created_at          timestamp
    updated_at          timestamp

# group_has_users

    user_id             bigint          foreign -> users.id
    group_id            bigint          foreign -> groups.id

    primary(['user_id','group_id], 'group_has_users_user_id_group_id_primary')

# group_has_schedules

    schedule_id         bigint          foreign -> schedules.id
    group_id            bigint          foreign -> groups.id

    primary(['schedule_id','group_id], 'group_has_schedules_schedule_id_group_id_primary')

# users

    id                  bigint          primary, unsigned
    username            string          unique
    name                string
    email               string          unique
    email_verified_at   timestamp       nullable
    password            string          hash
    remember_token      string          
    created_at          timestamp
    updated_at          timestamp

# user_has_tags

    tag_id              bigint          foreign -> tags.id
    user_id             bigint          foreign -> users.id

    primary(['tag_id','user_id], 'user_has_tags_tag_id_user_id_primary')

# user_has_attendances

    attendances_id      bigint          foreign -> attendances.id
    user_id             bigint          foreign -> users.id

    primary(['attendance_id','user_id], 'user_has_attendances_attendance_id_user_id_primary')

# tags

    id                  bigint          primary, unsigned
    uuid                string          unique
    mac_add             string          unique
    created_at          timestamp
    updated_at          timestamp

# tag_data_logs (not confirmed)

    id                  bigint          primary, unsigned   
    uuid                string
    mac_add             string
    major               bigint
    minor               bigint
    rssi                bigint          signed
    reader_id           string
    first_detected_at   datetime?
    last_detected_at    datetime?
    uploaded_at         datetime?

# attendances

    id                  bigint          primary, unsigned
    date                date
    clock_in            timestamp
    clock_out           timestamp
    total_time_spent    timestamp

# schedules

    id                  bigint          primary, unsigned
    name                string
    start_time          time
    end_time            time
    created_at          timestamp
    updated_at          timestamp

# schedule_has_buildings

    building_id         bigint      foreign -> buildings.id
    schedule_id         bigint      foreign -> schedules.id

    primary(['building_id','schedule_id], 'schedule_has_buildings_building_id_schedule_id_primary')


# buildings

    id                  bigint          primary, unsigned
    name                string
    detail              string          nullable
    address             string
    created_at          timestamp
    updated_at          timestamp

# building_has_readers

    reader_id           bigint      foreign -> readers.id
    building_id         bigint      foreign -> buildings.id

    primary(['reader_id','building_id], 'building_has_readers_reader_id_building_id_primary')

# readers

    id                  bigint          primary, unsigned
    uuid                string          unique
    mac_add             string          unique
    location            string
    created_at          timestamp
    updated_at          timestamp

