//
//  User.h
//  Studyr
//
//  Created by connor foody on 3/2/15.
//  Copyright (c) 2015 Jonathan Patsenker. All rights reserved.
//

#ifndef Studyr_User_h
#define Studyr_User_h

@interface User : NSObject{
    // no instance vars here
}

//@property (copy) NSString* model;

// all users have a name, rating and classes
// some users have photos, blurbs

- (id) init : (int) id_; // everything needs and ID
- (id) initBasic: (int) id_ :(NSString*) name;
- (id) initWithAll: (int)id_ :(NSString*)name :(NSArray*)classes :(NSString*)major :(int) rating;

// getters
- (NSString*) getName;
- (int) getRating;
- (NSArray*) getClasses;
- (NSString*) getMajor;
- (NSString*) printUser;
- (int) getID;


// setters
- (void) setName:(NSString*) name;
- (void) setRating:(int) rating;
// do we care about classes having different teachers?
- (void) addClass:(NSString*) subject;
- (void) removeClass:(NSString*) subject;
- (void) setMajor:(NSString*) major;
- (void) setID: (int) id_;


@end

#endif