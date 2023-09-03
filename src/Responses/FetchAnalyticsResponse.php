<?php

/*
 * Copyright (c) 2022 MynaParrot
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Mynaparrot\Plugnmeet\Responses;

use Mynaparrot\Plugnmeet\Utils\AnalyticsInfo;

/**
 *
 */
class FetchAnalyticsResponse extends BaseResponse
{
    /**
     * @return int
     */
    public function getTotalAnalytics(): int
    {
        if (!isset($this->rawResponse->result->total_analytics)) {
            return 0;
        }
        return $this->rawResponse->result->total_analytics;
    }

    /**
     * @return int
     */
    public function getFrom(): int
    {
        if (!isset($this->rawResponse->result->from)) {
            return 0;
        }
        return $this->rawResponse->result->from;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        if (!isset($this->rawResponse->result->limit)) {
            return 20;
        }
        return $this->rawResponse->result->limit;
    }

    /**
     * @return string
     */
    public function getOrderBy(): string
    {
        if (!isset($this->rawResponse->result->order_by)) {
            return "DESC";
        }
        return $this->rawResponse->result->order_by;
    }

    /**
     * @return AnalyticsInfo[]
     */
    public function getAnalytics(): array
    {
        $analytics = [];
        if (!isset($this->rawResponse->result)) {
            return $analytics;
        }

        if (count($this->rawResponse->result->analytics_list) > 0) {
            foreach ($this->rawResponse->result->analytics_list as $analytic) {
                $analytics[] = new AnalyticsInfo($analytic);
            }
        }

        return $analytics;
    }
}
