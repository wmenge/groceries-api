<?php 

namespace App\Services;

class SortService
{
    /***
     *
     * Adds sort expression to given $query object
     *
     */
    public static function addSortExpression($query, $sortString)
    {
        $sortList = SortService::parseSortExpression($sortString);
        if (empty($sortList)) {
            return $query;
        }

        foreach ($sortList as $expression) {
            $query->orderBy($expression->field, $expression->order);
        }

        return $query;
    }

    /***
     * Facade function to parse a sort querystring expression.
     *
     * Converts: "-title,type"
     *
     * to:
     * [
     *     { field: "title", order: "desc" },
     *     { field: "type", order: "asc" }
     * ]
     *
     * Does not check if fieldnames actually exist
     */
    private static function parseSortExpression($sortString)
    {
        $expressions = array();
        $tokens = mb_split(',', $sortString);

        foreach ($tokens as $token) {

            $trimmedToken = trim($token);
            $firstChar = mb_substr($trimmedToken, 0, 1);

            $expression = new \stdClass();

            if ($firstChar == '-') {
                $expression->field = trim(mb_substr($trimmedToken, 1));
                $expression->order = 'desc';
            } else {
                $expression->field = $trimmedToken;
                $expression->order = 'asc';
            }

            if (!empty($expression->field)) {
                $expressions[] = $expression;
            }
        }

        return $expressions;
    }
}
