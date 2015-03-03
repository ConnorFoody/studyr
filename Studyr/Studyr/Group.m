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

- (id) init{
    return [self initBasic:@"" :nil];
}

- (id) initBasic:(NSString *)name :(User *)user{
    if(user == nil){
        return [self initFull:name:nil:@""];
    }
    return [self initFull:name :[NSArray arrayWithObject:user] :@""];
}

- (id) initFull:(NSString *)name :(NSArray *)members :(NSString *)description{
    self = [super init];
    if(self){
        m_name = [name copy];
        if(members == nil){
            NSLog(@"WARNING: attempting to add nil members to group\n");
            m_members = nil;
        }
        else{
            m_members = [members mutableCopy];
        }
        m_description = [description copy];
    }
    return self;
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

- (NSString*) printGroup{
    NSString* members = @"";
    for(int i = 0; i < [m_members count]; i++){
        User* tmp = m_members[i];
        members = [members stringByAppendingFormat:@"%@ ", tmp.getName];
    }
    return [NSString stringWithFormat:@"GROUP: name(%@) description(%@) members(%@) rating(%d)",
                                                                                        m_name,
                                                                                        m_description,
                                                                                        members,
                                                                                        self.getRating];
}

- (void) addMember:(User *)member{
    if(m_members == nil){
        m_members = [NSMutableArray arrayWithObject:member];
    }
    else if([self isInGroup:member] > -1){
        NSLog(@"WARNING: tried to add a member, %@, that already exists in group %@", member.getName, m_name);
    }
    else{
        [m_members addObject:member];
    }
}

- (void) removeMember:(User *)member{
    if([self isInGroup:member] > -1){
        // compare members of group by name for now, maybe give a better ID later
        [m_members removeObjectAtIndex: [self isInGroup:member]];
    }
    else{
        NSLog(@"WARNING: tried to remove a member, %@, that does not exist in group %@", member.getName, m_name);
    }
}


- (int) isInGroup:(User *)member{
    // test for group membership. return index if in group or -1 if not
    if(m_members == nil){
        return -1;
    }
    for(int i = 0; i < [m_members count]; i++){
        if([m_members[i] getName] == [member getName]){
            return i;
        }
    }
    return -1;
}

- (void) setName:(NSString *)name{
    m_name = name;
}

- (void) setDescription:(NSString *)description{
    m_description = description;
}
@end