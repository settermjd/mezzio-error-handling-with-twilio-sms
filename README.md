# Mezzio Error Handling With Twilio SMS

This project provides an example of how to handle errors in Mezzio applications using Twilio SMS. 
See [the docs](https://docs.mezzio.dev/mezzio/v3/features/error-handling/) for more complete details on error handling in Mezzio application.

## Usage

### Set up the application

First, create a new application based on the project by running the following command and change into the new project directory by running the following commands.

```bash
composer create-project settermjd/mezzio-error-handling-with-twilio-sms mezzio-error-handling-with-twilio-sms
cd mezzio-error-handling-with-twilio-sms 
```

Then, set the four required environment variables in _.env_:

- Your Twilio Account SID, Auth Token, and phone number (`TWILIO_ACCOUNT_SID`, `TWILIO_AUTH_TOKEN`, `TWILIO_PHONE_NUMBER`). 
  These can be retrieved from the **Account Info** section of [the Twilio Console](https://console.twilio.com).
- The phone number to receive the error notification SMS (`SMS_RECIPIENT`).

After that, start the application by running the following command, in the root directory of the project.

```bash
composer serve
```

The application will now be available at http://localhost:8080.

### Trigger an error and a subsequent SMS

To trigger an error, first update the end of _src/App/src/Handler/HomePageHandler.php_ as follows:

```php
  
        throw new Exception('Something went wrong');
        
        return new HtmlResponse($this->template->render('app::home-page', $data));
    }
}
```

Then, either load http://localhost:8080 in your browser of choice, or run the following curl request.

```bash
curl http://localhost:8080
```

A few moments later, you should receive an SMS similar to the following example.

![An example SMS that the application will send when an error occurs.](/docs/images/example-sms.png)