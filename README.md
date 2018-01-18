# parse-email

This is a simple email parser without any libraries.
Tested with gmail successfully.

 gmail settings for imap, though this needs to be turned on within gmail:
 # imap.gmail.com:993
 # example: {imap.gmail.com:993/ssl}INBOX
 
 email - just username. No need for @gmail.com
 
 Next Steps:
 
 -save common email client configuration and allow more simple input, such as "google, yahoo, etc" - validate this input, and throw exceptions for any unknowns
 -check mailboxes other than Inbox
 -specify how many recent emails data to pull
 -MIME types to seperate out images and/or attachments
 -pull more info such as message size
 -sort based on input such as size, date, etc
