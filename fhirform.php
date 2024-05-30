
  "resourceType": "Patient",
  "id": $data[0],
  "meta": {
    "versionId": "1",
    "lastUpdated": "2024-05-23T16:58:33.474+00:00",
    "source": "#Pvo08oiL72MECZKZ"
  },
  "identifier": [ {
    "use": "usual",
    "type": {
      "coding": [ {
        "system": "http://hl7.org/fhir/v2/0203",
        "code": "MR"
      } ]
    },
    "system": "http://hospital.smarthealthit.org",
    "value": "12345"
  } ],
  "name": [ {
    "use": "official",
    "family": "Doe",
    "given": [ "John" ]
  } ],
  "gender": "male",
  "birthDate": "1970-01-01",
  "address": [ {
    "use": "home",
    "line": [ "123 Main St" ],
    "city": "Anytown",
    "state": "Anystate",
    "postalCode": "12345",
    "country": "USA"
  } ]
}