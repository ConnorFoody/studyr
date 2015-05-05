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

- (id) init{
    self = [super init];
    return self;
}

// TODO: write unit test to check parsing correctly and correct errors

- (User*) parseDictionaryToUser: (id) reply error:(NSError**) error{
    if([reply isKindOfClass:[NSDictionary class]]){
        // just pass the input
        User* tmp = [self buildUserFromDictionary:reply error:error];
        // print the error here so we can see it going down
        if(*error != nil){
            NSLog(@"%@:%s Error parsing input: %@", [self class], _cmd, [(*error) localizedDescription]);
            return nil;
        }
        return tmp;
    }
    else{
        NSMutableDictionary* errorInfo = [NSMutableDictionary dictionary];
        [errorInfo setValue:@"Incorrect type, expected dictionary\n" forKey:NSLocalizedDescriptionKey];
        *error = [NSError errorWithDomain:@"com.studyr.studyr" code: 10 userInfo:errorInfo];
        NSLog(@"%@:%s Error parsing input: %@", [self class], _cmd, [(*error) localizedDescription]);
    }
    return nil;
}
- (Group*) parseDictionaryToGroup: (id) reply error:(NSError**) error{
    if([reply isKindOfClass:[NSDictionary class]]){
        // just pass the input down the chain for now
        Group* tmp = [self buildGroupFromDictionary:reply error:error];
        // print the error here so we can see it going down
        if(*error != nil){
            NSLog(@"%@:%s Error parsing input: %@", [self class], _cmd, [(*error) localizedDescription]);
            return nil;
        }
        return tmp;
    }
    else{
        NSMutableDictionary* errorInfo = [NSMutableDictionary dictionary];
        [errorInfo setValue:@"Incorrect type, expected dictionary\n" forKey:NSLocalizedDescriptionKey];
        *error = [NSError errorWithDomain:@"com.studyr.studyr" code: 101 userInfo:errorInfo];
        NSLog(@"%@:%s Error parsing input: %@", [self class], _cmd, [(*error) localizedDescription]);
    }
    return nil;
}
- (NSArray*) parseArrayToGroupArray:(id)reply error:(NSError**) error{
    if([reply isKindOfClass:[NSArray class]]){
        NSMutableArray* groups = [NSMutableArray arrayWithObject:nil];
        // go through and add each group to the array one by one
        for(int i = 0; i < [reply count]; i++){
            if([reply[i] isKindOfClass:[NSDictionary class]]){
                Group* tmp = [self buildGroupFromDictionary:reply[i] error:error];
                
                // print the error here so we can see it going down
                if(error != nil){
                    NSLog(@"%@:%s Error parsing input: %@", [self class], _cmd, [(*error) localizedDescription]);
                    return nil;
                }
                // if no error, add object
                [groups addObject: tmp];
            }
            else{
                NSMutableDictionary* errorInfo = [NSMutableDictionary dictionary];
                [errorInfo setValue:@"Incorrect type, expected array of dictionaries" forKey:NSLocalizedDescriptionKey];
                *error = [NSError errorWithDomain:@"com.studyr.studyr" code: 102 userInfo:errorInfo];
                NSLog(@"%@:%s Error parsing input: %@", [self class], _cmd, [(*error) localizedDescription]);
                return nil;
            }
        }
        // if we go through alright, return
        return [groups copy];
    }
    else{
        NSMutableDictionary* errorInfo = [NSMutableDictionary dictionary];
        [errorInfo setValue:@"Incorrect type, expected array\n" forKey:NSLocalizedDescriptionKey];
        *error = [NSError errorWithDomain:@"com.studyr.studyr" code: 103 userInfo:errorInfo];
        NSLog(@"%@:%s Error parsing input: %@", [self class], _cmd, [(*error) localizedDescription]);
    }
    return nil;
}


- (User*) buildUserFromDictionary:(NSDictionary *)reply error:(NSError**) error {
    // parser functions make sure these inputs are valid
    
    User *user;
    NSNumber* user_id;
    NSNumber* user_rating;
    NSString* user_class;
    NSString* user_name;
    
    if( [[reply objectForKey:@"id"] isKindOfClass: [NSNumber class]]
       && [reply objectForKey:@"id"] != nil ){
        user_id = [reply objectForKey:@"id"];
    }
    else{
        NSMutableDictionary* errorInfo = [NSMutableDictionary dictionary];
        [errorInfo setValue:@"Incorrect type, expected NSNumber\n" forKey:NSLocalizedDescriptionKey];
        *error = [NSError errorWithDomain:@"com.studyr.studyr" code: 104 userInfo:errorInfo];
    }
    
    if( [[reply objectForKey:@"rating"] isKindOfClass: [NSNumber class]]
       && [reply objectForKey:@"rating"] != nil ){
        user_rating = [reply objectForKey:@"rating"];
    }
    else{
        NSMutableDictionary* errorInfo = [NSMutableDictionary dictionary];
        [errorInfo setValue:@"Incorrect type, expected NSNumber\n" forKey:NSLocalizedDescriptionKey];
        *error = [NSError errorWithDomain:@"com.studyr.studyr" code: 105 userInfo:errorInfo];
    }
    
    if( [[reply objectForKey:@"class"] isKindOfClass: [NSString class]]
        && [reply objectForKey:@"class"] != nil ){
        user_class = [reply objectForKey:@"class"];
    }
    else{
        NSMutableDictionary* errorInfo = [NSMutableDictionary dictionary];
        [errorInfo setValue:@"Incorrect type, expected NSString\n" forKey:NSLocalizedDescriptionKey];
        *error = [NSError errorWithDomain:@"com.studyr.studyr" code: 106 userInfo:errorInfo];
    }
    
    if( [[reply objectForKey:@"username"] isKindOfClass: [NSString class]]
       && [reply objectForKey:@"username"] != nil ){
        user_name = [reply objectForKey:@"username"];
    }
    else{
        NSMutableDictionary* errorInfo = [NSMutableDictionary dictionary];
        [errorInfo setValue:@"Incorrect type, expected NSString\n" forKey:NSLocalizedDescriptionKey];
        *error = [NSError errorWithDomain:@"com.studyr.studyr" code: 107 userInfo:errorInfo];
    }
    
    if(*error != nil){
        NSLog(@"%@:%s Error parsing input: %@", [self class], _cmd, [(*error) localizedDescription]);
        return nil;
    }
    NSArray* classes_array = [NSArray arrayWithObjects:user_class, nil];
    user = [[User alloc] initWithId: [user_id integerValue] name: user_name classes: classes_array major: @"" rating: [user_rating integerValue]];
    
    
    return user;
};

- (Group*) buildGroupFromDictionary:(NSDictionary *)reply error:(NSError**) error {
    // parser functions make sure these inputs are valid
    
    Group* group;
    
    NSString* group_name;
    NSArray* group_members;
    NSString* group_description;
    NSNumber* group_id;
    
    if([[reply objectForKey:@"groupname"] isKindOfClass: [NSString class]]
       && [reply objectForKey:@"groupname"] != nil ){
        group_name = [reply objectForKey:@"groupname"];
    }
    else{
        NSMutableDictionary* errorInfo = [NSMutableDictionary dictionary];
        [errorInfo setValue:@"Incorrect type, expected NSString\n" forKey:NSLocalizedDescriptionKey];
        *error = [NSError errorWithDomain:@"com.studyr.studyr" code: 108 userInfo:errorInfo];
    }
    
    if([[reply objectForKey:@"members"] isKindOfClass: [NSArray class]]
       && [reply objectForKey:@"members"] != nil && [[reply objectForKey:@"members"] count] > 0){
        group_members = [reply objectForKey:@"members"];
    }
    else{
        NSMutableDictionary* errorInfo = [NSMutableDictionary dictionary];
        [errorInfo setValue:@"Incorrect type, expected NSArray with size > 0" forKey:NSLocalizedDescriptionKey];
        *error = [NSError errorWithDomain:@"com.studyr.studyr" code: 109 userInfo:errorInfo];
    }
    
    if([[reply objectForKey:@"description"] isKindOfClass: [NSString class]]
       && [reply objectForKey:@"description"] != nil ){
        group_description = [reply objectForKey:@"description"];
    }
    else{
        NSMutableDictionary* errorInfo = [NSMutableDictionary dictionary];
        [errorInfo setValue:@"Incorrect type, expected NSString\n" forKey:NSLocalizedDescriptionKey];
        *error = [NSError errorWithDomain:@"com.studyr.studyr" code: 110 userInfo:errorInfo];
    }
    
    if([[reply objectForKey:@"id"] isKindOfClass: [NSNumber class]]
       && [reply objectForKey:@"id"] != nil ){
        group_id = [reply objectForKey:@"id"];
    }
    else{
        NSMutableDictionary* errorInfo = [NSMutableDictionary dictionary];
        [errorInfo setValue:@"Incorrect type, expected NSNumber\n" forKey:NSLocalizedDescriptionKey];
        *error = [NSError errorWithDomain:@"com.studyr.studyr" code: 110 userInfo:errorInfo];
    }
    
    // is this an OK way to treat the error?
    // if there are multiple errors, this would only return the last one
    // returning the first error has the same issue though...
    if(*error != nil){
        NSLog(@"%@:%s Error parsing input: %@", [self class], _cmd, [(*error) localizedDescription]);
        return nil;
    }
    
    group = [[Group alloc] initWithId:[group_id integerValue] name:group_name members: nil description: group_description];
    
    // build users from json dictionaries and insert one by one
    for(int i = 0; i < [group_members count]; i++){
        User* tmp = [self buildUserFromDictionary:group_members[i] error:error];
        if(tmp == nil){
            // print error here so we can see it's path going down
            NSLog(@"%@:%s Error parsing input: %@", [self class], _cmd, [(*error) localizedDescription]);
            return nil;
        }
        // if no type error, add group member
        [group addMember:tmp];
    }
    return group;
};

@end