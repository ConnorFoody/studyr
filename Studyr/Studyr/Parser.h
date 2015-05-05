//
//  Parser.h
//  Studyr
//
//  Created by connor foody on 5/4/15.
//  Copyright (c) 2015 Jonathan Patsenker. All rights reserved.
//

#ifndef Studyr_Parser_h
#define Studyr_Parser_h

#include "User.h"
#include "Group.h"

@interface Parser : NSObject{
    // no instance vars
}

// parse json replies to output types
// set error = nil before passing it in
// returns nil if an issue occured
- (User*) parseDictionaryToUser: (id) reply error:(NSError**) error;
- (Group*) parseDictionaryToGroup: (id) reply error:(NSError**) error;
- (NSArray*) parseArrayToGroupArray: (id) reply error:(NSError**) error;

// delegate funcitons to build user object from dictionaries
// called by the parse functions
- (User*) buildUserFromDictionary: (NSDictionary*) reply error:(NSError**) error;
- (Group*) buildGroupFromDictionary: (NSDictionary*) reply error:(NSError**) error;
@end
#endif
