# sso
sso module easily usable in CO


## FEATURES 
- [CO](/connect/co/co) : connecting with CO login service 
- [Fairkom](/connect/co/fairkom) : connecting with Fairkom's Open ID Connect protocol 
- [Github](/connect/co/oauth)
- Mastodon
- Diaspora

## Process

### Github 
- connect
- github auth > return to CO 
- record user into CO DB, set session userId, and fill user data 
    - name 
    - description
    - image
    - url
    - organization 
    - orgs https://api.github.com/users/xxxx/orgs
    - location
    - email

### Fairkom


### Homepage
/connect shows the Readme.md content information

## TODO

## Ideas, proposals; wishList
- use CO.graph to read github tree of users
- view a github organization in CO using interop

## BUGS, ACTIONS



