//
//  Group.m
//  Studyr
//
//  Created by connor foody on 3/2/15.
//  Copyright (c) 2015 Jonathan Patsenker. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "Group.h"

@implementation Group{
    NSString* m_name;
    NSMutableArray* m_members;
    NSString* m_description;
}

- (NSString*) getName{
    return m_name;
}
- (NSString*) getDescription{
    return m_description;
}
- (NSArray*) getMembers{
    return m_members;
}

- (int) getRating{
    if([m_members count] == 0){
        return 0;
    }
    int rating = 0;
    for(int i =0; i < [m_members count]; i++){
        User* tmp = m_members[i];
        rating += tmp.getRating;
    }
    return rating / [m_members count];
}

- (void) addMember:(User *)member{
    if([m_members containsObject:member]){
        NSLog(@"WARNING: tried to add a member, %@, that already exists in group %@", member.getName, m_name);
    }
    else{
        [m_members addObject:member];
    }
}

- (void) removeMember:(User *)member{
    if([m_members containsObject:m_members]){
        [m_members removeObject:member];
    }
    else{
        NSLog(@"WARNING: tried to remove a member, %@, that does not exist in group %@", member.getName, m_name);
    }
}

- (void) setName:(NSString *)name{
    m_name = name;
}

- (void) setDescription:(NSString *)description{
    m_description = description;
}


@end