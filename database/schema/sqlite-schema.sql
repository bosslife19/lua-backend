CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar,
  "email" varchar not null,
  "date_of_birth" datetime,
  "gender" varchar,
  "fitness_level" varchar,
  "fitness_goal" varchar,
  "cycle_behaviour" varchar,
  "menstrual_start" datetime,
  "legal_name" varchar,
  "preferred_name" varchar,
  "otp_code" integer,
  "otp_expires_at" datetime,
  "intentions" varchar,
  "period_type" varchar,
  "birth_control" varchar,
  "cycle_regularity" varchar,
  "flow_type" varchar,
  "menstrual_symptoms" varchar,
  "exercise_experience" varchar,
  "exercise_frequency" varchar,
  "daily_activity_level" varchar,
  "additional_info" varchar,
  "movement_space" varchar,
  "health_conditions" varchar,
  "wellness_support_methods" varchar,
  "movement_considerations" varchar,
  "recent_surgical_procedures" varchar,
  "pregnancy" varchar,
  "movement_response" varchar,
  "healthcare_provider" varchar,
  "additional_health_info" varchar,
  "heart_condition_or_hbp" tinyint(1),
  "chest_pain" tinyint(1),
  "lost_consciousness" tinyint(1),
  "other_chronic_condition" tinyint(1),
  "medication_for_chronic_condition" tinyint(1),
  "bone_or_ligament_problem" tinyint(1),
  "medically_supervised_activity" tinyint(1),
  "pronouns" varchar,
  "email_verified_at" datetime,
  "password" varchar,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "personal_access_tokens"(
  "id" integer primary key autoincrement not null,
  "tokenable_type" varchar not null,
  "tokenable_id" integer not null,
  "name" varchar not null,
  "token" varchar not null,
  "abilities" text,
  "last_used_at" datetime,
  "expires_at" datetime,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" on "personal_access_tokens"(
  "tokenable_type",
  "tokenable_id"
);
CREATE UNIQUE INDEX "personal_access_tokens_token_unique" on "personal_access_tokens"(
  "token"
);
CREATE TABLE IF NOT EXISTS "exercises"(
  "id" integer primary key autoincrement not null,
  "title" varchar not null,
  "description" text,
  "scheduled_for" date not null,
  "duration" integer not null,
  "equipments" varchar not null,
  "instructions" varchar not null,
  "trainer_notes" varchar not null,
  "working_muscles" varchar,
  "supporting_muscles" varchar,
  "level" varchar not null,
  "saved" tinyint(1) not null default '0',
  "thumbnail" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  "videos" text
);
CREATE TABLE IF NOT EXISTS "workout_time_logs"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "date" date not null,
  "seconds_watched" integer not null default '0',
  "exercises_done" text,
  "status" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "exercise_user"(
  "id" integer primary key autoincrement not null,
  "exercise_id" integer not null,
  "user_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("exercise_id") references "exercises"("id") on delete cascade,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "notifications"(
  "id" integer primary key autoincrement not null,
  "type" varchar check("type" in('missed_workout', 'trainer_message', 'reminder', 'new_videos', 'new_personal_video')) not null,
  "title" varchar not null,
  "message" varchar not null,
  "read" tinyint(1) not null default '0',
  "user_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime
);

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2025_03_31_094418_create_personal_access_tokens_table',1);
INSERT INTO migrations VALUES(5,'2025_04_09_205123_create_exercises_table',1);
INSERT INTO migrations VALUES(6,'2025_04_11_112716_create_workout_time_logs_table',1);
INSERT INTO migrations VALUES(7,'2025_04_15_210641_create_exercise_user_table',1);
INSERT INTO migrations VALUES(8,'2025_04_21_094032_create_notifications_table',1);
INSERT INTO migrations VALUES(9,'2025_04_28_082032_update_workouts_table_for_multiple_videos',2);
