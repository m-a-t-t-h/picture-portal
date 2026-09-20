<script setup>
import {useStateStore} from "../services/state.js";
import {computed} from "vue";
import "vue-data-ui/style.css"; // If you are using multiple components, place this style import in your main
import {VueUiTable} from "vue-data-ui/vue-ui-table";

const state = useStateStore();
state.page.has_footer = false;
state.page.has_header = false;

const conf = computed(() => {
    return {
        "devHints": {"enable": false},
        "fontFamily": "inherit",
        "maxHeight": 500,
        "rowsPerPage": 25,
        "useCursorPointer": false,
        "style": {
            "title": {
                "text": "",
                "color": "#2D353C",
                "fontSize": 20,
                "bold": true,
                "textAlign": "center",
                "paddingLeft": 0,
                "paddingRight": 0,
                "subtitle": {"color": "#A1A1A1", "text": "", "fontSize": 16, "bold": false},
                "backgroundColor": "#FFFFFF"
            },
            "th": {
                "backgroundColor": "#e1e5e8",
                "color": "#2D353C",
                "outline": "1px solid #FFFFFF",
                "selected": {"backgroundColor": "#1f77b4", "color": "#FFFFFF"},
                "buttons": {
                    "filter": {
                        "inactive": {"backgroundColor": "#e1e5e8", "color": "#2D353C"},
                        "active": {"backgroundColor": "#1f77b4", "color": "#FFFFFF"}
                    },
                    "cancel": {
                        "inactive": {"backgroundColor": "#e1e5e8", "color": "#2D353C"},
                        "active": {"backgroundColor": "#F17171", "color": "#FFFFFF"}
                    }
                }
            },
            "rows": {
                "outline": "1px solid #FFFFFF",
                "even": {
                    "backgroundColor": "#f3f5f7",
                    "color": "#2D353C",
                    "selectedCell": {"backgroundColor": "#1f77b45b", "color": "#2D353C"},
                    "selectedNeighbors": {"backgroundColor": "#63dd821e", "color": "#2D353C"}
                },
                "odd": {
                    "backgroundColor": "#FFFFFF",
                    "color": "#2D353C",
                    "selectedCell": {"backgroundColor": "#1f77b45b", "color": "#2D353C"},
                    "selectedNeighbors": {"backgroundColor": "#63dd821e", "color": "#2D353C"}
                }
            },
            "inputs": {
                "backgroundColor": "#FFFFFF",
                "color": "#2D353C",
                "border": "1px solid #e1e5e8",
                "accentColor": "#1f77b4"
            },
            "dropdowns": {
                "backgroundColor": "#e1e5e8",
                "color": "#2D353C",
                "icons": {
                    "selected": {"color": "#2ca02c", "unicode": "✔"},
                    "unselected": {"color": "#d62728", "unicode": "✖"}
                }
            },
            "infoBar": {"backgroundColor": "#e1e5e8", "color": "#2D353C"},
            "pagination": {
                "buttons": {"backgroundColor": "#e1e5e8", "color": "#2D353C", "opacityDisabled": 0.5},
                "navigationIndicator": {"backgroundColor": "#1f77b4"}
            },
            "exportMenu": {
                "show": true,
                "backgroundColor": "#e1e5e8",
                "color": "#2D353C",
                "buttons": {"backgroundColor": "#fafafa", "color": "#2D353C"},
                "filename": ""
            },
            "closeButtons": {"backgroundColor": "transparent", "color": "#2D353C", "borderRadius": "50%"},
            "chart": {
                "modal": {
                    "backgroundColor": "#e1e5e8",
                    "color": "#2D353C",
                    "buttons": {
                        "selected": {"backgroundColor": "#1f77b4", "color": "#FFFFFF"},
                        "unselected": {"backgroundColor": "#FFFFFF", "color": "#2D353C"}
                    }
                },
                "layout": {
                    "backgroundColor": "#FFFFFF",
                    "axis": {"stroke": "#ccd1d4", "strokeWidth": 2},
                    "bar": {"fill": "#1f77b4", "stroke": "#FFFFFF"},
                    "line": {
                        "smooth": true,
                        "useArea": false,
                        "stroke": "#1f77b4",
                        "strokeWidth": 4,
                        "plot": {
                            "fill": "#1f77b4",
                            "stroke": "#FFFFFF",
                            "strokeWidth": 1,
                            "radius": {"selected": 6, "unselected": 4}
                        },
                        "selector": {"stroke": "#ccc", "strokeWidth": 1, "strokeDasharray": 5}
                    },
                    "labels": {"color": "#2D353C"},
                    "progression": {"stroke": "#2D353C", "strokeWidth": 2, "strokeDasharray": 4, "arrowSize": 7},
                    "timeLabels": {"showOnlyAtModulo": true, "modulo": 2},
                    "datetimeFormatter": {
                        "enable": true,
                        "locale": "en",
                        "useUTC": false,
                        "januaryAsYear": false,
                        "options": {
                            "year": "yyyy",
                            "month": "MMM 'yy",
                            "day": "dd MMM",
                            "hour": "HH:mm",
                            "minute": "HH:mm:ss",
                            "second": "HH:mm:ss"
                        }
                    },
                    "zoom": {"show": true, "autoFit": true}
                }
            }
        },
        "translations": {
            "average": "Average",
            "by": "by",
            "chooseCategoryColumn": "Choose category column",
            "exportAllButton": "CSV all",
            "exportAllLabel": "Export all rows of your current filtered dataset",
            "exportPageButton": "CSV page",
            "exportPageLabel": "Export rows of the current page",
            "from": "From",
            "inputPlaceholder": "Search...",
            "makeDonut": "Generate",
            "nb": "Nb",
            "page": "Page",
            "paginatorLabel": "Rows per page",
            "sizeWarning": "Displaying too many rows at a time can lead to slower performance",
            "sum": "Sum",
            "to": "To",
            "total": "Total",
            "totalRows": "Total rows",
            "filename": "File name",
            "xAxisLabels": "X axis labels"
        },
        "useChart": true
    };
});
const data = computed(() => {
    return {
        header: [
            {
                name: "touchpoint",
                type: "text",
                average: false,
                decimals: undefined,
                sum: false,
                isSort: true,
                isSearch: 1,
                isMultiselect: 1,
                isPercentage: false,
                percentageTo: undefined,
                suffix: "",
                prefix: "",
                rangeFilter: false,
            },
            {
                name: "category",
                type: "text",
                average: false,
                decimals: undefined,
                sum: false,
                isSort: true,
                isSearch: 1,
                isMultiselect: 0,
                isPercentage: false,
                percentageTo: undefined,
                suffix: "",
                prefix: "",
                rangeFilter: 0,
            },
            {
                name: "date",
                type: "date",
                average: false,
                decimals: undefined,
                sum: false,
                isSort: false,
                isSearch: false,
                isMultiselect: false,
                isPercentage: false,
                percentageTo: undefined,
                suffix: "",
                prefix: "",
                rangeFilter: false,
            },
            {
                name: "base",
                type: "numeric",
                average: 1,
                decimals: 0,
                sum: 1,
                isSort: false,
                isSearch: false,
                isMultiselect: false,
                isPercentage: false,
                percentageTo: undefined,
                suffix: "",
                prefix: "",
                rangeFilter: false,
            },
            {
                name: "rating",
                type: "numeric",
                average: 1,
                decimals: 1,
                sum: false,
                isSort: false,
                isSearch: false,
                isMultiselect: false,
                isPercentage: false,
                percentageTo: undefined,
                suffix: "",
                prefix: "",
                rangeFilter: false,
            },
            {
                name: "spend",
                type: "numeric",
                average: false,
                decimals: 1,
                sum: 1,
                isSort: false,
                isSearch: false,
                isMultiselect: false,
                isPercentage: false,
                percentageTo: undefined,
                suffix: '€',
                prefix: "",
                rangeFilter: false,
            },
            {
                name: "percentage",
                type: "numeric",
                average: false,
                decimals: 1,
                sum: false,
                isSort: false,
                isSearch: false,
                isMultiselect: false,
                isPercentage: true, // requires an empty array in the body 'td' arrays!
                percentageTo: "base",
                suffix: "",
                prefix: "",
                rangeFilter: false,
            },
            {
                name: "happy",
                type: "numeric",
                average: false,
                decimals:
                    0
                ,
                sum: false,
                isSort: false,
                isSearch: false,
                isMultiselect: false,
                isPercentage: false,
                percentageTo: "base",
                suffix: "",
                prefix: "",
                rangeFilter: false,
            },
            {
                name: "sad",
                type: "numeric",
                average: false,
                decimals:
                    0
                ,
                sum: false,
                isSort: false,
                isSearch: false,
                isMultiselect: false,
                isPercentage: false,
                percentageTo: "base",
                suffix: "",
                prefix: "",
                rangeFilter: false,
            }
        ],
        body: [
            // A few rows as an example
            {
                td: [
                    "Réactivité du support",
                    "Accueil",
                    "2023-11-14",
                    35,
                    4.1,
                    18.7,
                    "", // notice the empty string, due to a config of the column with isPercentage = true and percentageTo set to another column
                    -27,
                    109
                ],
            },
            {
                td: [
                    "Variété des produits",
                    "Caisse",
                    "2023-08-12",
                    25,
                    2,
                    74,
                    "", // notice the empty string, due to a config of the column with isPercentage = true and percentageTo set to another column
                    35,
                    276
                ]
            },
        ]
    }
});




</script>

<template>

        <VueUiTable :config="conf" :dataset="data"/>

</template>
