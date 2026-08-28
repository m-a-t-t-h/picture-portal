<?php

return [
    // ---- The top level collection ID, as defined in AlbumRoots
    //
    "ROOT_COLLECTION_ID"              => env("DKW_ROOT_COLLECTION_ID"),

    // ---- JSON encoded array of tag IDs to show at the root level of the tree
    //
    "ROOT_TAG_ARRAY"                  => env("DKW_ROOT_TAG_ARRAY"),

    // ---- Whether to require images to be served to have the "Public" tag,
    //      otherwise an image will be considered private
    //
    "REQUIRE_PUBLIC_TAG"              => env("DKW_REQUIRE_PUBLIC_TAG"),

    // ---- The ID value of the "Public" tag
    //
    "PUBLIC_TAG_ID"                   => env("DKW_PUBLIC_TAG_ID"),

    // ---- How many images to pre-load in each batch
    //
    "INFINITE_SCROLL_PAGE_SIZE"       => env("DKW_INFINITE_SCROLL_PAGE_SIZE"),

    // ---- Where to put the sentinel image to trigger the next batch loading
    //
    "INFINITE_SCROLL_SENTINEL_OFFSET" => env("DKW_INFINITE_SCROLL_SENTINEL_OFFSET"),

    "PAGE_SIZE" => env("DKW_INFINITE_SCROLL_PAGE_SIZE"),

    // ---- The DigiKam tag that corresponds to the "OnThisDay" label
    //
    "ON_THIS_DAY_TAG_ID" => env("DKW_ON_THIS_DAY_TAG_ID")
];
