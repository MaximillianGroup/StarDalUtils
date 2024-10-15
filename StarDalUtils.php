<?php

namespace Includes\Utils\Data;

/**
 * StarDALUtils - WordPress HTML Generator Plugin
 *
 * @package    StarDALUtils - WordPress database-related helper functions.
 * @author     Max Barrett <maximilliangroup@gmail.com>
 * @link       https://github.com/MaximillianGroup/StarDalUtils
 * @since      1.0.0
 */

// If this file is called directly, abort.
if (!defined('ABSPATH') || !defined('WPINC')) {
	exit;
}

class StarDALUtils
{
    public static array $starTables = [
        "entities",
        "artists",
        "releases",
        "works",
        "masters",
        "producers",
        "transcriptions",
        "copyrights",
        "royalties",
        "popularity",
        "events",
        "venues",
        "tags",
        "taxonomies",
        "attributes",
        "countries",
        "languages",
        "entity_tags",
        "entity_taxonomies",
        "entity_attributes",
        "content_distributions",
        "distribution_links",
        "external_platform_accounts",
        "payment_history",
        "analytics",
        "platform_data",
        "distributed_assets",
        "user_sessions",
        "users",
        "usermeta",
        "firebase_user_metadata",
        "user_role_assignments",
        "asset_access_control",
        "api_tokens",
        "user_api_tokens",
        "user_services",
        "encryption_keys"
    ];

    protected static array $defaultTables = [
        "wp_commentmeta",
        "wp_comments",
        "wp_links",
        "wp_options",
        "wp_postmeta",
        "wp_posts",
        "wp_terms",
        "wp_termmeta",
        "wp_term_relationships",
        "wp_term_taxonomy",
        "wp_usermeta",
        "wp_users"
    ];

    public static array $defaultMultiSiteTables = [
        "blogmeta",
        "blogs",
        "site",
        "sitemeta",
        "users"
    ];

    /**
     * Check if a table is in the Star database schema.
     *
     * @param string $table The table name.
     * @return bool True if it's a Star table.
     */
    public static function star_isStarTable(string $table): bool
    {
        // Checks if the table exists in the custom Star schema tables array
        return in_array($table, self::$starTables);
    }

    /**
     * Check if a table is a WordPress default table.
     *
     * @param string $table The table name.
     * @return bool True if it's a default WordPress table.
     */
    public static function star_isDefaultTable(string $table): bool
    {
        // Checks if the table exists in WordPress default tables array
        return in_array($table, self::$defaultTables);
    }

    /**
     * Check if a table is a WordPress multi-site default table.
     *
     * @param string $table The table name.
     * @return bool True if it's a multi-site table.
     */
    public static function star_isDefaultMultiSiteTable(string $table): bool
    {
        // Checks if the table exists in WordPress multi-site tables array
        return in_array($table, self::$defaultMultiSiteTables);
    }

    /**
     * Check if a table is a WordPress table (default or multi-site).
     *
     * @param string $table The table name.
     * @return bool True if it's a WP table.
     */
    public static function star_isWPTable(string $table): bool
    {
        // Check if the table is either a default or multi-site table in WordPress
        if (self::star_isDefaultTable($table)) {
            return true;
        }
        if (self::star_isDefaultMultiSiteTable($table)) {
            return true;
        }
        return false;
    }

    /**
     * Check if a table is either in the Star database or a WordPress table.
     *
     * @param string $table The table name.
     * @return bool True if the table is recognized.
     */
    public static function star_isTable(string $table): bool
    {
        // Check if the table is in the Star schema or any WP tables
        if (self::star_isStarTable($table)) {
            return true;
        }
        if (self::star_isWPTable($table)) {
            return true;
        }
        return false;
    }

    /**
     * Validates and returns a safe output format for SQL queries.
     *
     * @param mixed $output The output format to validate.
     * @return mixed The validated output format, defaulting to OBJECT.
     */
    public static function star_validOutput(mixed $output): mixed
    {
        // Allow only specific formats, default to OBJECT if not valid
        if (in_array($output, [OBJECT, OBJECT_K, ARRAY_N, ARRAY_A])) {
            return $output;
        }
        return OBJECT;
    }

    /**
     * Applies prefix and sanitizes the table name for database use.
     *
     * @param string $table The base table name.
     * @param string|null $prefix Optional custom prefix, defaults to WP prefix.
     * @return string The prefixed and sanitized table name.
     */
    public static function star_getPrefixedTableName(string $table, ?string $prefix = null): string
    {
        global $wpdb;
        // Default to WordPress prefix if no custom prefix is provided
        $prefix = $prefix ?? $wpdb->prefix;
        return $wpdb->quote_identifier($prefix . sanitize_key($table));
    }

    /**
     * Sanitizes a column name to prevent SQL injection.
     *
     * @param string $column The column name.
     * @return string The sanitized column name.
     * @throws InvalidArgumentException If the column name is invalid.
     */
    public static function star_sanitizeColumnName(string $column): string
    {
        // Validates column name for alphanumeric and underscore characters
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
            throw new InvalidArgumentException("Invalid column name: {$column}");
        }
        return sanitize_key($column);
    }

    /**
     * Checks if a table name has a valid prefix.
     *
     * @param string $tableName The table name to check.
     * @return bool True if the prefix is valid.
     */
    public static function star_hasValidPrefix(string $tableName): bool
    {
        global $wpdb;
        
        // Check if the table name starts with the WordPress prefix
        if (substr($tableName, 0, strlen($wpdb->prefix)) === $wpdb->prefix) {
            return true;
        }
        
        // Check if the table name starts with the DarkMatter custom prefix
        if (defined('DARKMATTER_DB_PREFIX') && substr($tableName, 0, strlen(DARKMATTER_DB_PREFIX)) === DARKMATTER_DB_PREFIX) {
            return true;
        }
        
        // Return false if no prefix matches
        return false;
    }
}
