API Documentation
*****************

PHPUnit
*******
In root folder, run ./vendor/bin/phpunit

OAuth Client
************
User must first login, create an OAuth client, and then go to
http://wework.evolutionaryascension.com/issueToken to authorize
the app and create a access token.

API Endpoints
*************

1. List Notes: Retrives the list of notes
**************
Method: GET /api/notes/{page}/{limit}
Param 1 (optional): page
Param 2 (optional): limit

Returns: JSON {
  "success": true/false,
  "data": [
    {
      "id": 100,
      "user_id": 1,
      "message": "test1",
      "tags": null,
      "created_at": "2016-12-11 11:11:11",
      "updated_at": "2016-12-11 11:11:11",
      "deleted_at": null
    },
    {
      "id": 101,
      "user_id": 1,
      "message": "test2",
      "tags": "[\"tag2\"]",
      "created_at": "2016-12-11 11:11:11",
      "updated_at": "2016-12-11 11:11:11",
      "deleted_at": null
    },
  "error": ""
}

2. Create Note: Creates a note
***************
Method: POST /api/note
Input 1: message [text]
Input 2: tags [json array]

Returns: JSON {
  "success": true/false,
  "data": {
    "user_id": 1,
    "message": "test test",
    "tags": "[\"tag1\"]",
    "updated_at": "2017-03-30 13:19:29",
    "created_at": "2017-03-30 13:19:29",
    "id": 2
  },
  "error": ""
}

3. Update Note: updates a note
***************
Method: PUT /api/note/{id}
Param 1: id
Input 1: message [text]
Input 2: tags [json array]

Returns: JSON {
  "success": true/false,
  "data": {
    "user_id": 1,
    "message": "test test",
    "tags": "\"tag1\"",
    "updated_at": "2017-03-30 13:19:29",
    "created_at": "2017-03-30 13:19:29",
    "id": 2
  },
  "error": ""
}

4. Delete Note: deletes a note
***************
Method: DELETE /api/note/{id}
Param 1: id

Returns: JSON {
  "success": true/false,
  "error": ""
}
