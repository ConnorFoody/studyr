//
//  TestParser.m
//  Studyr
//
//  Created by connor foody on 5/5/15.
//  Copyright (c) 2015 Jonathan Patsenker. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <UIKit/UIKit.h>
#import <XCTest/XCTest.h>

#include "Parser.h"

@interface ParserTests : XCTestCase

@end

@implementation ParserTests

- (void)setUp {
    [super setUp];
    // Put setup code here. This method is called before the invocation of each test method in the class.
}

- (void)tearDown {
    // Put teardown code here. This method is called after the invocation of each test method in the class.
    [super tearDown];
}

- (void)testParseUser {
    // parse user data with all data there
    NSError* error = nil;
    NSDictionary* user_data = @{
                                @"id" : [NSNumber numberWithInt:1],
                                @"rating" : [NSNumber numberWithInt:4],
                                @"class" : @"bio",
                                @"username":@"connor",
                                };
    Parser* parser = [[Parser alloc] init];
    
    NSLog(@"\n\nTesting good user data\n");
    User* good_user = [parser parseDictionaryToUser:user_data error:&error];
    
    XCTAssert(good_user != nil);
    XCTAssert(error == nil);
    XCTAssert([good_user getID] == 1);
    XCTAssert([[good_user getClasses] count] == 1);
    XCTAssert([[good_user getClasses][0] isEqualToString:@"bio"]);
    XCTAssert([[good_user getName] isEqualToString:@"connor"]);
    XCTAssert([good_user getRating] == 4);
    
    NSDictionary* bad_type_user_data = @{
                                @"id" : [NSNumber numberWithInt:1],
                                @"rating" : [NSNumber numberWithInt:4],
                                @"class" : [NSNumber numberWithInt:4],
                                @"username":@"connor",
                                };
    
    // user data with invalid type
    NSLog(@"\n\nTesting bad type user data\n");
    User* bad_type_user = [parser parseDictionaryToUser:bad_type_user_data error:&error];
    
    XCTAssert(bad_type_user == nil);
    XCTAssert(error != nil);
    
    // user data missing a field
    NSDictionary* missing_field_user_data = @{
                                @"rating" : [NSNumber numberWithInt:4],
                                @"class" : @"bio",
                                @"username":@"connor",
                                };
    
    error = nil;
    NSLog(@"\n\nTesting missing field user data\n");
    User* missing_field_user = [parser parseDictionaryToUser:missing_field_user_data error:&error];
    
    XCTAssert(missing_field_user == nil);
    XCTAssert(error != nil);
    
    error = nil;
    NSArray* array = [NSArray arrayWithObjects:bad_type_user_data, user_data, nil];
    User* bad_input = [parser parseDictionaryToUser:array error:&error];
    
    XCTAssert(error != nil);
    XCTAssert(bad_input == nil);
}

- (void)testParseGroup{
    NSError* error = nil;
    NSDictionary* user_data = @{
                                @"id" : [NSNumber numberWithInt:1],
                                @"rating" : [NSNumber numberWithInt:4],
                                @"class" : @"bio",
                                @"username":@"connor",
                                };
    
    NSDictionary* group_data = @{
                                 @"id": [NSNumber numberWithInt:1],
                                 @"description": @"a group",
                                 @"members": [NSArray arrayWithObjects: user_data, nil],
                                 @"groupname": @"name",
                                 };
    Parser* parser = [[Parser alloc] init];
    NSLog(@"\n\ntesting parse good group\n");
    Group* good_group = [parser parseDictionaryToGroup:group_data error:&error];
    
    XCTAssert(error == nil);
    XCTAssert(good_group != nil);
    XCTAssert([good_group getID] == 1);
    XCTAssert([[good_group getDescription] isEqualToString:@"a group"]);
    XCTAssert([[good_group getName] isEqualToString:@"name"]);
    XCTAssert([[good_group getMembers] count] == 1);
    XCTAssert([[good_group getMembers][0] getRating] == 4);
    
    NSDictionary* bad_type_group_data = @{
                                 @"id": [NSNumber numberWithInt:1],
                                 @"description": [NSNumber numberWithInt:1],
                                 @"members": [NSArray arrayWithObjects: user_data, nil],
                                 @"groupname": @"name",
                                 };
    
    NSLog(@"\n\ntesting bad type group\n");
    Group* bad_type_group = [parser parseDictionaryToGroup:bad_type_group_data error:&error];
    
    XCTAssert(error != nil);
    XCTAssert(bad_type_group == nil);
    
    error = nil;
    
    NSDictionary* missing_field_group_data = @{
                                 @"id": [NSNumber numberWithInt:1],
                                 @"members": [NSArray arrayWithObjects: user_data, nil],
                                 @"groupname": @"name",
                                 };
    
    NSLog(@"\n\ntesting missing field group\n");
    Group* missing_field_group = [parser parseDictionaryToGroup:missing_field_group_data error:&error];
    
    XCTAssert(error != nil);
    XCTAssert(missing_field_group == nil);
    
    error = nil;
    
    NSDictionary* bad_user_data = @{
                                @"id" : [NSNumber numberWithInt:1],
                                @"rating" : [NSNumber numberWithInt:4],
                                @"class" : @"bio",
                                };
    
    NSDictionary* user_missing_field_group_data = @{
                                 @"id": [NSNumber numberWithInt:1],
                                 @"description": @"a group",
                                 @"members": [NSArray arrayWithObjects: user_data, bad_user_data, nil],
                                 @"groupname": @"name",
                                 };
    
    NSLog(@"\n\ntesting user missing field group\n");
    Group* user_missing_field_group = [parser parseDictionaryToGroup:user_missing_field_group_data error:&error];
    
    XCTAssert(error != nil);
    XCTAssert(user_missing_field_group == nil);
    
    error = nil;
    
    NSArray* array = [NSArray arrayWithObjects:bad_user_data, user_data, nil];
    NSLog(@"\n\ntesting array input group\n");
    Group* array_input = [parser parseDictionaryToGroup:array error:&error];
    XCTAssert(error != nil);
    XCTAssert(array_input == nil);
}
@end