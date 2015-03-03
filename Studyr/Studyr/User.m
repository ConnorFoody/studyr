//
//  User.m
//  Studyr
//
//  Created by connor foody on 3/2/15.
//  Copyright (c) 2015 Jonathan Patsenker. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "User.h"

@implementation User{
    NSString* m_name;
    int m_rating;
    NSMutableArray* m_classes;
    NSString* m_major;
}

- (id) init{
    return [self initBasic:@""];
}
- (id) initBasic:(NSString *)name{
    return [self initWithAll:name :nil : @"" : 0];
}
- (id) initWithAll:(NSString *)name :(NSArray *)classes :(NSString *)major :(int)rating{
    self = [super init];
    if(self){
        m_name = [name copy];
        if(classes == nil){
            NSLog(@"WARNING: attempting to add nil classes to user\n");
            m_classes = nil;
        }
        else{
            m_classes = [classes mutableCopy];
        }
        m_major = [major copy];
        m_rating = rating;
    }
    return self;
}
// getters
- (NSString*) getName{
    return m_name;
}

- (int) getRating{
    return m_rating;
}

- (NSArray*) getClasses{
    return m_classes;
}

- (NSString*) getMajor{
    return m_major;
}

- (NSString*) printUser{
    NSString* classes = @"";
    for(int i = 0; i < [m_classes count]; i++){
        classes = [ classes stringByAppendingFormat:@"%@ ", m_classes[i]];
    }
    return [NSString stringWithFormat:@"USER: name(%@) classes(%@) major(%@) rating(%d)",
                                                            m_name,
                                                            classes,
                                                            m_major,
                                                            m_rating];
}

// setters

- (void) setName:(NSString *)name{
    m_name = name;
}

- (void) setRating:(int)rating{
    m_rating = rating;
}

- (void) addClass:(NSString *)subject{
    // am I checking contains properly? If not, also check removeClass
    if(m_classes == nil){
        m_classes = [NSMutableArray arrayWithObject:subject];
    }
    else if([m_classes containsObject:subject]){
        NSLog(@"WARNING: tried to add a class, %@, that already exists in user %@\n", subject, self.getName);
    }
    else{
        [m_classes addObject:subject];
    }
}

- (void) removeClass:(NSString *)subject{
    if([m_classes containsObject:subject]){
        [m_classes removeObject:subject];
    }
    else{
        NSLog(@"WARNING: tried to remove a class, %@, that does not exist in user %@\n", subject, self.getName);
    }
}

- (void) setMajor:(NSString *)major{
    m_major = major;
}





@end