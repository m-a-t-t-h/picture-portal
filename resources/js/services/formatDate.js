// ---- Import this class to format dates in templates
//
// @see https://jerickson.net/how-to-format-dates-in-vue-3/

import dayjs from 'dayjs';

export default {
        formatDate(dateString) {
            const date = dayjs(dateString);
            // Then specify how you want your dates to be formatted
            return date.format('dddd MMMM D, YYYY');
        }
}
