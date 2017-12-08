
The application uses two data sources to download and save schools and postcodes data. The size of the data is probably too large to download in real time. To update the data download using the command "make download" or using the equivalent command in a script.

The web form passes the postcode and max. distance in the query string to the application. The postcode is used to locate the coordinates using an Open Data API. Using the coordinates the school data file is read, and any schools which are found to be within the location mapping bounded by the max. distance radius from the postcode's location.
