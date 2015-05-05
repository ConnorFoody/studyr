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


// parser functions, filler for now
- (User*) parseDictionaryToUser: (id) reply;
- (Group*) parseDictionaryToGroup: (id) reply;
- (NSArray*) parseDictionaryToGroupArray: (id) reply;

// delegate funcitons to build user object from dictionaries
- (User*) buildUserFromDictionary: (NSDictionary*) reply;
- (Group*) buildGroupFromDictionary: (NSDictionary*) reply;
@end
#endif
