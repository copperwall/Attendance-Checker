# Event Attendance Tracker

I recently discovered that Facebook allows you to export event guest lists in CSV format, and decided to make a little project out of it.

This web app requires a couple of dependencies.

* MySQL
* A "Guest List.csv" from a Facebook Event
* A web server to handle GET and POST requests, and run PHP (Nginx and Apache should do just fine)
* jQuery
* Bootstrap 3

## The Big Idea

By downloading a Facebook Event guest list CSV file, you can import the data
into a MySQL table and keep track of people who have showed up to the event.
The list of invited friends is displayed in a responsive web view, which is
easily viewable on both tablets and phones. By default the list is sorted
alphabetically by name, and then by their invitation status on Facebook. By
clicking the button next a persons name, they are removed from the page and
marked as present in your corresponding MySQL table.

![screen shot 2014-05-28 at 12 52 33 am](https://cloud.githubusercontent.com/assets/2539016/3101963/f413c55a-e63e-11e3-822d-5645e4bbebf2.png)

## Limitations

This does not pull live data from the facebook event, as I haven't explored
Facebook's API enough to log the user in to recieve their event data. The CSV
file has to be downloaded manually and copied to the correct file name.
Currently, the MySQL table must be cleared between updating the guest list,
otherwise those who are already in the database will be double counted.

This application is also somewhat tedious to set up, as you'll have be familiar
enough with MySQL to create a table and give the correct privileges to your
MySQL user accounts. Some of the configuration must be done outside of config
files, in the actual source. That being said, it has been made much more
configurable than when it was first written. It was also written in six hours,
so some of the methods may be somewhat unorthodox (authenticating with cookies,
sorry about that)
