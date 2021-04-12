CREATE DEFINER=`admin`@`%` PROCEDURE `attendance_check`(IN `new_tag_mac` VARCHAR(17) CHARACTER SET utf8mb4, 
IN `new_reader_mac` VARCHAR(17) CHARACTER SET utf8mb4, 
IN `new_created_at` DATETIME
)
BEGIN SELECT id, last_seen, first_seen, reader_mac, tag_mac into 
        @id1, @last_seen1, @first_seen1, @mac, @tag 
        from attendances AS att 
        WHERE att.tag_mac = new_tag_mac COLLATE  utf8mb4_unicode_ci 
        AND att.reader_mac = new_reader_mac COLLATE  utf8mb4_unicode_ci 
        ORDER by id desc limit 1; 
        
        
        IF ((@last_seen1 > DATE_SUB(convert_tz(NOW(), '+00:00', '+08:00'),INTERVAL 5 MINUTE) OR @last_seen1 IS NULL) AND @first_seen1 IS NOT NULL) 
        THEN
        UPDATE attendances 
        SET last_seen = new_created_at 
        WHERE id = @id1; 
        ELSE 
        
        INSERT INTO attendances(tag_mac, reader_mac, first_seen, `date`, duration) 
        VALUES (new_tag_mac, new_reader_mac, new_created_at, CAST(new_created_at AS DATE), "0:00:00"); 
        END IF; 
        END