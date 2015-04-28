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

//@property (copy) NSString* model;

- (id) init: (int) id_;
- (id) initWithId: (int)id_ name: (NSString*)name user: (User*)user;
- (id) initWithId: (int)id_ name:(NSString*)name members: (NSArray*)members description: (NSString*) description;

- (NSString*) getName;
- (NSArray*) getMembers;
- (int) getRating; // can figure out rating from members
- (NSString*) getDescription;
- (NSString*) printGroup;
- (int) getID; // every group has an ID

- (int) isInGroup:(User*) member;
- (void) addMember:(User*) member;
- (void) removeMember:(User*) member;
- (void) setDescription:(NSString*) description;
- (void) setName:(NSString*) name;
- (void) setID: (int) id_;

// also define a description and chat object?
@end
#endif