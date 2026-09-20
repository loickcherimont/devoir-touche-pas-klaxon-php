<?php

namespace App;

/**
 * Formatter
 *
 * Small stateless helpers used by the templates to display dates.
 * Centralizes the format so it is not duplicated in every template (DRY).
 */
final class Formatter
{
    /**
     * Formats a date-like value as d/m/Y.
     *
     * @param string $value Date string (ex: '2026-10-18')
     * @return string Formatted date, or '' when the value cannot be parsed
     */
    public static function date(string $value): string
    {
        $timestamp = strtotime($value);

        return $timestamp === false ? '' : date('d/m/Y', $timestamp);
    }

    /**
     * Formats a time-like value as H:i.
     *
     * @param string $value Time string (ex: '14:44:00')
     * @return string Formatted time, or '' when the value cannot be parsed
     */
    public static function time(string $value): string
    {
        $timestamp = strtotime($value);

        return $timestamp === false ? '' : date('H:i', $timestamp);
    }
}