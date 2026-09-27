<?php

namespace App;

use DateTimeImmutable;

/**
 * Formatter
 *
 * Small stateless helpers to display dates.
 * Centralizes the format so it is not duplicated in every template (DRY).
 */
final class DateTimeFormatter
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

    /**
     * Combines a date and a time submitted by a form into a single
     * DateTimeImmutable, ready to be compared or stored.
     *
     * @param string $date Date part (ex: '2026-10-18').
     * @param string $time Time part (ex: '14:44').
     * @return DateTimeImmutable The parsed departure/arrival date and time.
     * @throws \Exception When the date and the time cannot be parsed.
     */
    public static function getDatetimeFormat(string $date, string $time): DateTimeImmutable
    {
        return new DateTimeImmutable("$date $time");
    }
}