# Nalo Solutions SMS API Developer Documentation

## Introduction
The Nalo Solutions SMS API provides an HTTP interface for sending SMS messages through the Nalo Solutions SMS Gateway. This API allows developers to integrate SMS functionality into their applications, enabling quick message delivery to mobile numbers. The API supports both HTTPS GET and POST requests, with authentication via username/password or an API key.

## Prerequisites
To use the SMS API, you need:
- A Nalo Solutions account.
- A username and password or an API key (generated from the client portal).
- A registered sender ID (e.g., `NALO` or a custom ID).
- A tool for making HTTP requests (e.g., cURL, Postman, or a programming language like Python with the `requests` library).
- Familiarity with RESTful APIs, JSON, and XML.

## Authentication
The SMS API supports two authentication methods:
1. **Username and Password**: Provide your account's username and password in the request parameters.
2. **API Key**: Use a manually generated key from the client portal instead of username and password.

### Obtaining an API Key
1. Log in to the Nalo Solutions client portal.
2. Navigate to the API key generation section.
3. Generate a new API key and store it securely.

### Using Authentication
- **With Username and Password**: Include `username` and `password` in the request parameters.
- **With API Key**: Include the `key` parameter in the request, ensuring it is URL-encoded.

## Base URL
All API requests should be made to the following base URL:
```
https://sms.nalosolutions.com/smsbackend
```

## Sending SMS Messages
The SMS API supports sending messages via HTTPS GET and POST requests. Messages can be sent to one or multiple recipients, with parameters provided in JSON, XML, or URL-encoded formats.

### 1. HTTPS GET API
The GET API is used to send SMS messages by passing parameters in the URL query string.

#### Endpoint
```http
GET /clientapi/{Username_prefix}/send-message/
```

#### Parameters
| Parameter         | Required | Description                                                                 |
|-------------------|----------|-----------------------------------------------------------------------------|
| `Username_prefix` | Yes      | Specifies the reseller's name (e.g., `Resl_Nalo`).                          |
| `username`        | Yes      | Unique username chosen during account creation.                             |
| `password`        | Yes      | Password (minimum 8 characters, alphanumeric with at least one special character). |
| `type`            | Yes      | Message type (e.g., `0` for standard SMS).                                  |
| `destination`     | Yes      | Recipient phone number (e.g., `233XXXXXXXXX`).                              |
| `dir`             | Yes      | Direction flag (e.g., `1` for outbound).                                    |
| `source`          | Yes      | Sender ID (e.g., `NALO`).                                                  |
| `message`         | Yes      | URL-encoded message content (e.g., `This+is+a+test+from+Mars`).             |

**Example Request (Username & Password):**
```http
GET https://sms.nalosolutions.com/smsbackend/clientapi/Resl_Nalo/send-message/?username=johndoe&password=some_password&type=0&destination=233XXXXXXXXX&dir=1&source=NALO&message=This+is+a+test+from+Mars
```

**Example Request (API Key):**
```http
GET https://sms.nalosolutions.com/smsbackend/clientapi/Resl_Nalo/send-message/?key={{AUTH_KEY}}&type=0&destination=233XXXXXXXXX&dir=1&source=NALO&message=This+is+a+test+from+Mars
```

**Note**: URL-encode the API key to avoid issues with special characters.

### 2. HTTPS POST API (JSON)
The POST API allows sending SMS messages with parameters in a JSON body.

#### Endpoint
```http
POST /Resl_Nalo/send-message/
```

#### Headers
- `Content-Type: application/json`

#### Parameters
| Parameter    | Required | Description                                              |
|--------------|----------|----------------------------------------------------------|
| `username`   | Yes*     | Username chosen during account creation.                 |
| `password`   | Yes*     | Password for the account.                                |
| `key`        | Yes*     | API key (used instead of username and password).         |
| `msisdn`     | Yes      | Comma-separated recipient phone numbers (e.g., `233244071872,233x00000000`). |
| `message`    | Yes      | Message content.                                        |
| `sender_id`  | Yes      | Sender ID (e.g., `NALO` or `Test`).                     |

*Either `username` and `password` or `key` is required, not both.

**Example Request (JSON with API Key):**
```http
POST https://sms.nalosolutions.com/smsbackend/Resl_Nalo/send-message/
Content-Type: application/json

{
    "key": "MCav5guzevj1)0)1w3gh(ehg2d4x0(#ih7jkmk2gpi987)6530xadkyjxlgzi",
    "msisdn": "233244071872,233x00000000",
    "message": "Here are two, of many",
    "sender_id": "NALO"
}
```

**Example Request (JSON with Username & Password):**
```http
POST https://sms.nalosolutions.com/smsbackend/Resl_Nalo/send-message/
Content-Type: application/json

{
    "username": "johndoe",
    "password": "password",
    "msisdn": "233x00000000",
    "message": "Here are two, of many",
    "sender_id": "Test"
}
```

### 3. HTTPS POST API (XML)
The POST API also supports sending SMS messages with parameters in an XML body.

#### Endpoint
```http
POST /Resl_Nalo/send-sms/
```

#### Headers
- `Content-Type: application/xml`

#### Parameters
| Parameter    | Required | Description                                              |
|--------------|----------|----------------------------------------------------------|
| `username`   | Yes      | Username chosen during account creation.                 |
| `password`   | Yes      | Password for the account.                                |
| `msisdn`     | Yes      | Recipient phone number (e.g., `233x00000000`).           |
| `sender_id`  | Yes      | Sender ID (e.g., `MySender`).                           |
| `message`    | Yes      | Message content.                                        |

**Example Request (XML):**
```http
POST https://sms.nalosolutions.com/smsbackend/Resl_Nalo/send-sms/
Content-Type: application/xml

<message>
    <username>johndoe</username>
    <password>password</password>
    <msisdn>233x00000000</msisdn>
    <sender_id>MySender</sender_id>
    <message>Here are two, of many</message>
</message>
```

### 4. HTTPS POST API (XML T24)
The XML T24 format is an alternative XML structure for sending SMS messages.

#### Endpoint
```http
POST /Resl_Nalo/send-sms/
```

#### Headers
- `Content-Type: application/xml`

#### Parameters
| Parameter | Required | Description                                              |
|-----------|----------|----------------------------------------------------------|
| `user`    | Yes      | Username chosen during account creation.                 |
| `password`| Yes      | Password for the account.                                |
| `from`    | Yes      | Sender ID (e.g., `NALO`).                               |
| `to`      | Yes      | Recipient phone number (e.g., `233x00000000`).           |
| `text`    | Yes      | Message content.                                        |

**Note**: The XML T24 format was not fully detailed in the provided document, but it follows a similar structure to the standard XML request.

## Response Codes
The API returns response codes to indicate the success or failure of a request.

### Success Response
- **Code**: `1701`
- **Description**: Message submitted successfully.
- **Format**: `1701|<phone_number>|<message_ID>`
- **Example**: `1701|233244071872|ATXid_123`
- **Note**: The `message_ID` can be used to map delivery reports to the message.

### Error Responses
| Code | Description                              |
|------|------------------------------------------|
| 1702 | Invalid URL Error (missing or blank parameter). |
| 1703 | Invalid value in username or password field. |
| 1704 | Invalid value in `type` field.           |
| 1705 | Invalid message content.                 |
| 1706 | Invalid destination (recipient phone number). |
| 1707 | Invalid source (sender ID).              |
| 1708 | Invalid value for `dlr` field.           |
| 1709 | User validation failed.                  |
| 1710 | Internal error.                          |
| 1025 | Insufficient credit (user).              |
| 1026 | Insufficient credit (reseller).          |

## Error Handling
Handle errors by checking the response code and message. Common issues include:
- **Invalid Parameters**: Ensure all required parameters are provided and valid (e.g., correct phone number format).
- **Authentication Failure**: Verify that the username, password, or API key is correct.
- **Insufficient Credit**: Check your account balance in the client portal.

## Best Practices
- **Secure Credentials**: Store usernames, passwords, and API keys securely. Avoid exposing them in client-side code or public repositories.
- **URL-Encode Parameters**: Always URL-encode the API key and message content in GET requests to handle special characters.
- **Validate Phone Numbers**: Ensure recipient numbers are in the correct format (e.g., `233XXXXXXXXX`).
- **Monitor Credit**: Regularly check your account balance to avoid `1025` or `1026` errors.
- **Test in a Sandbox**: Use a test environment to validate your integration before sending live messages.

## Additional Notes
- The API supports sending messages to multiple recipients in a single request by including comma-separated phone numbers in the `msisdn` parameter.
- The `dir` parameter (direction flag) is typically set to `1` for outbound messages.
- The `type` parameter (message type) is typically set to `0` for standard SMS, but additional types may be supported (not detailed in the provided document).