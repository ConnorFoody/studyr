//
//  Parser.m
//  Studyr
//
//  Created by connor foody on 5/4/15.
//  Copyright (c) 2015 Jonathan Patsenker. All rights reserved.
//

#import <Foundation/Foundation.h>
#include "Parser.h" 


@implementation Parser{
    
}

- (User*) parseDictionaryToUser: (id) reply{
    if([reply isKindOfClass:[NSDictionary class]]){
        return [self buildUserFromDictionary:reply];
    }
    // TODO: actual error handling
    return [[User alloc] init:-1];
}
- (Group*) parseDictionaryToGroup: (id) reply{
    if([reply isKindOfClass:[NSDictionary class]]){
        return [self buildGroupFromDictionary:reply];
    }
    // TODO: actual error handling
    return [[Group alloc] init:-1];
}
- (NSArray*) parseDictionaryToGroupArray:(id)reply{
    if([reply isKindOfClass:[NSArray class]]){
        NSMutableArray* groups = [NSMutableArray arrayWithObject:nil];
        // go through and add each group to the array one by one
        for(int i = 0; i < [reply count]; i++){
            // TODO: actual error handling
            if([reply[i] isKindOfClass:[NSDictionary class]]){
                [groups addObject:[self buildUserFromDictionary:reply[i]]];
            }
        }
        return [groups copy];
    }
    return [NSArray arrayWithObject:nil];
}


- (User*) buildUserFromDictionary:(NSDictionary *)reply {
    
    User *user;
    NSNumber* user_id;
    NSNumber* user_rating;
    NSString* user_class;
    NSString* user_name;
    
    // TODO: handle type errors
    if( [[reply objectForKey:@"id"] isKindOfClass: [NSNumber class]]){
        user_id = [reply objectForKey:@"id"];
    }
    if( [[reply objectForKey:@"rating"] isKindOfClass: [NSNumber class]]){
        user_rating = [reply objectForKey:@"rating"];
    }
    if( [[reply objectForKey:@"class"] isKindOfClass: [NSString class]]){
        user_class = [reply objectForKey:@"classes"];
    }
    if( [[reply objectForKey:@"username"] isKindOfClass: [NSString class]]){
        user_name = [reply objectForKey:@"username"];
    }
    
    user = [[User alloc] initWithId: [user_id integerValue] name: user_name classes: [[NSArray alloc] initWithObjects: user_class, nil] major: @"" rating: [user_rating integerValue]];
    
    return user;
};

- (Group*) buildGroupFromDictionary:(NSDictionary *)reply {
    
    Group* group;
    
    NSString* group_name;
    NSArray* group_members;
    NSString* group_description;
    NSNumber* group_id;
    
    // TODO: handle type errors
    if([[reply objectForKey:@"groupname"] isKindOfClass: [NSString class]]){
        group_name = [reply objectForKey:@"groupname"];
    }
    if([[reply objectForKey:@"members"] isKindOfClass: [NSArray class]]){
        group_members = [reply objectForKey:@"members"];
    }
    if([[reply objectForKey:@"description"] isKindOfClass: [NSString class]]){
        group_description = [reply objectForKey:@"description"];
    }
    if([[reply objectForKey:@"id"] isKindOfClass: [NSNumber class]]){
        group_id = [reply objectForKey:@"id"];
    }
    
    group = [[Group alloc] initWithId:[group_id integerValue] name:group_name members: nil description: group_description];
    
    // build users from json and insert one by one
    for(int i = 0; i < [group_members count]; i++){
        User* tmp = [self buildUserFromDictionary:group_members[i]];
        [group addMember:tmp];
    }
    return group;
};

@end