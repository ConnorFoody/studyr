//
//  Group.h
//  Studyr
//
//  Created by connor foody on 3/2/15.
//  Copyright (c) 2015 Jonathan Patsenker. All rights reserved.
//

#ifndef Studyr_Group_h
#define Studyr_Group_h
#include "User.h"
@interface Group : NSObject{
    
}

@property (copy) NSString* model;

- (NSString*) getName;
- (NSArray*) getMembers;
- (int) getRating; // can figure out rating from members
- (NSString*) getDescription;

- (void) addMember:(User*) member;
- (void) removeMember:(User*) member;
- (void) setDescription:(NSString*) description;
- (void) setName:(NSString*) name;

// also define a description and chat object?
@end


#endif
