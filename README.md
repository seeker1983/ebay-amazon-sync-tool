# ebay-amazon-sync-tool 

## What is the tool for?
This tool was designed for automatical scraping of goods from different vendors
(Amazon,Overstock,Hayneedle,Wayfair,Walmart supported now), listing them on ebay, and
automatically updating the price/availability based on vendor changes.

## Current state
I'm no longer working on this project, so probably scraping of most vendors are outdated now,
but many people seem to show interest in this project, so I decided to commit it "as is", and
provide basic instructions on how to setup and use it.

## Installation and usage.
As I'm probably not very good at writing very detailed instructions I decided to record 2 videos on how 
to setup and use this tool.
First video will be about how to set tool and get it running, second video will be about how to setup
1-click scraping for vendors.

## Installation
1. Unpacking files to your domain(it can be local for testing purposes)
2. Create a database.
3. Fill it with db.sql 
4. Set you database parameters in lib/config.php
5. Login using roma/123.
6. Create your own user.
7. Create javascript bookmarklet.

## User management
There can be 2 type of users - sandbox and real users. If you want to check the tool without paying
for actual listings, you can create sandbox ebay user and setup it's credentials here. By
default this tool comes with user roma and password 123. You can login using this user and create
your own user and setup credentials via dashboard.
Brief video is in user_setup.swf and at http://screencast.com/t/TzlKsqCAj

## Scraping setup
In order to setup 1-click scraping you have to create a bookmarklet. Take the code in comments
at bottom of scrap.js.
You can access the video either in scraper_setup.swf or http://screencast.com/t/7mMiF04qo

